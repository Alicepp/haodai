<?php

/**
 * 根据规则校验表单(POST)或指定数组值
 * @param string|array $rules 规则名或规则数组
 * @param array $data 待校验数据,为空时使用POST数据
 * @throws ArgumentException
 */
function checkForm($rules, array $data = array()) {
  static $form_validation = NULL;

  if (!isset($form_validation)) {
    $config = NULL;
    require BASEPATH . 'libraries/' . 'Form_validation.php';
    if (is_file($file = APPPATH . 'config/' . 'form_validation.php')) require $file;

    $form_validation = new CI_Form_validation($config);
    $form_validation->set_error_delimiters('', '');
  }
  !empty($data) && $form_validation->set_data($data);

  $form_validation->reset_validation();
  if (is_array($rules)) $result = $form_validation->set_rules($rules)->run();
  elseif (is_string($rules)) $result = $form_validation->run($rules);
  else throw new ArgumentException('API 数据验证调用异常', API_FAILURE);

  if (!$result) {
    $errors = $form_validation->error_array();
    if (empty($errors)) throw new ArgumentException('API 数据验证规则异常', API_FAILURE);
    else throw new ArgumentException(current($errors), API_FAILURE, key($errors));
  }

  return $result;
}

function getCiInstance() {
  return class_exists('CI_Controller', FALSE) ? CI_Controller::get_instance() : NULL;
}

function isInstalled($file, $date = NULL) {
  $result = is_file($file);

  $result && $date && $result = is_numeric($date) && strtotime($date) < filemtime($file);

  return $result;
}

/**
 * 返回最终调用的控制器名 [/目录]/类名/[方法/]
 * @param boolean $lower 是否转换为小写
 * @param boolean $method 是否获取方法名
 * @return string|boolean
 */
function getRealUri($lower = TRUE, $method = TRUE) {
  if ($ci = getCiInstance()) {
    $result = '/' . $ci->router->directory;

    if ($ci->router->class) $result .= $ci->router->class . '/';
    else { //根目录
      sscanf(trim($ci->router->default_controller, '/'), '%[^/]', $class);
      $result .= $class . '/';
    }

    $method && $result .= $ci->router->method . '/';
  }
  else $result = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

  return $lower ? strtolower($result) : $result;
}

/**
 * 根据注解区域规则，返回当前控制器的上一级链接 （默认返回 /）
 * @return string
 */
function getBackUrl() {
  $prefix = rtrim(config_item('variable_prefix'), '_-');
  $result = isset($_GET[$prefix . 'url'][0]) ? '/' . trim($_GET[$prefix . 'url'], '/') : NULL;

  if (!isset($result)) {
    ($ci = getCiInstance()) && ($class = $prefix . '_SiteMap') && LoadClass($class, 'core') && $result = $class::getBackUrl($ci->router->class,
        $ci->router->method);
  }

  return empty($result) ? '/' : $result;
}

//-----------------------------------------------------------------------------

function LoadClass($class, $directory = 'libraries') {
  if (!$result = class_exists($class, FALSE)) {
    is_file($file = APPPATH . trim($directory, '/\\') . '/' . $class . '.php') && require $file;
    $result = class_exists($class, FALSE);
  }

  return $result;
}

/**
 * 加载当前调用下的文件
 * @param string $filename 相对文件路径
 */
function import($filename) {
  $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 6);

  //if (!isset($backtrace['file'])) { //call_user_func
  //  $reflection = new ReflectionFunction($backtrace['function']);
  //  $backtrace['file'] = $reflection->getFileName();
  //  $backtrace['line'] = $reflection->getStartLine();
  //}

  foreach ($backtrace as $item) if (isset($item['file'])) break;

  if (isset($item['file'])) {
    $filename = realpath(dirname($item['file']) . '/' . $filename);
    if ($filename && is_file($filename)) return require $filename;
  }
}

function errorLog($message) {
  log_message('error', $message . PHP_EOL);
}

function debugLog($message) {
  if (!headers_sent()) {
    API_DEBUG && isLocalhost() && isApacheModule() && LoadClass('ChromePHP') && ChromePHP::info($message . PHP_EOL);
  }
  else $message = 'headers sent!!!' . PHP_EOL . $message;

  log_message('debug', $message . PHP_EOL);

  sendLog($message, 'debug');
}

function sendLog($message, $level = 'info') {
  defined('LOG_LOCAL') && with(qb_Logger::getInstance())->sendTelnet(LOG_LOCAL, $message, $level);

  defined('LOG_SYS') && with(qb_Logger::getInstance())->sendTcp(LOG_SYS, $message, $level);
}

/**
 * 将出错异常转发到组内日志系统
 */
function SetErrorHandler() {
  $old_error = set_error_handler(NULL);
  $old_exception = set_exception_handler(NULL);

  set_error_handler(function ($severity, $message, $filepath, $line) use ($old_error) {
    if ($severity !== E_DEPRECATED) { //@ error_reporting() === 0    E_DEPRECATED
      $msg = $_SERVER['REQUEST_URI'] . ' [' . $_SERVER['REMOTE_ADDR'] . ']' . PHP_EOL;
      $msg .= '[' . $severity . '] ' . $message . PHP_EOL . $filepath . ' [' . $line . ']';

      sendLog($msg, 'error');
    }

    $old_error && $old_error($severity, $message, $filepath, $line);
  });

  set_exception_handler(function ($exception) use($old_exception) {
    $msg = $_SERVER['REQUEST_URI'] . ' [' . $_SERVER['REMOTE_ADDR'] . ']' . PHP_EOL;
    $msg .= '[' . $exception->getCode() . '] ' . $exception->getMessage() . PHP_EOL .
      $exception->getFile() . ' [' . $exception->getLine() . ']';

    sendLog($msg, 'error');

    $old_exception && $old_exception($exception);
  });
}

function goRedirect($code, $url, $message = '操作超时，请重新登录', $request = TRUE) {
  $url = getRedirectStr($url, $request);

  if (isAjax()) showJsonMsg($code, $message, $url);
  else {
    //get_config(['base_url' => '/', 'index_page' => '']);
    redirect($url);
  }
}

/**
 * 获取或拼接链接 (禁止站外跳转)
 * @param string $url 此参数为空时,仅获取链接
 * @param boolean|string $request 是否使用 REQUEST_URI 或 指定字符串 替代
 * @return string
 */
function getRedirectStr($url = '', $request = FALSE) {
  $url && $url = '/' . trim($url, '/');
  $argurl = rtrim(config_item('variable_prefix'), '_-') . 'url';

  $result = isset($_GET[$argurl][0]) ? $_GET[$argurl] : NULL;
  if (!isset($result)) {
    if ($request === TRUE) {
      if (isAjax()) {
        $result = empty($_SERVER['HTTP_REFERER']) ? '' : strval(strstrex($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']));

        $result || $result = '/';
      }
      else $result = $_SERVER['REQUEST_URI'];
    }
    else $result = strval($request);
  }
  $result && $result = '/' . trim($result, '/');

  if ($result === $url) return $url;

  if (isset($url[0])) { //拼接链接
    $result = $result ? (strpos($url, '?') === FALSE ? '?' : '&') . $argurl . '=' . urlencode($result) : '';

    $result = $url . $result;
  }

  return strval($result);
}

function isAPP() {
  $argfrom = rtrim(config_item('variable_prefix'), '_-') . 'from';
  $from = empty($_SERVER['REQUEST_URI']) ? '' : $_SERVER['REQUEST_URI'] . '&';
  $from .= empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'] . '&';

  foreach (array('app', 'iso', 'android') as $item) {
    if (stripos($from, $argfrom . '=' . $item . '&') !== FALSE) return TRUE;
  }

  return FALSE;
}

function isMobile() {
  $useragent = $_SERVER['HTTP_USER_AGENT'];

  return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',
      $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
      substr($useragent, 0, 4));
}

function isPassword($password) { // /^((?=.*\d)(?=.*_)|(?=.*[a-zA-Z])(?=.*[\d_]))\w{6,20}$/
  return !empty($password) && preg_match('/^(?=[0-9a-zA-Z!@#$%^*()_.|;:,\?\/]{6,20}$)/', $password);
}

function isMobilephone($mobilePhone) {
  ///^0?(1[34578])[0-9]{9}$/
  return isset($mobilePhone[10]) && isUInt($mobilePhone);
}

//-----------------------------------------------------------------------------

function showError($message = API_FAILURE_MSG) {
  if (isAjax()) showJsonMsg(API_FAILURE, $message);
  else show_error($message);
}

/**
 * 输出JSON格式提示信息
 * @param mixed $code 状态码
 * @param string $msg 提示信息
 * @param string $url 跳转链接
 */
function showJsonMsg($code, $msg = NULL, $url = '') {
  header('Content-Type: application/json; charset=utf-8');
  die(jsonencode(_getJson($code, $msg, $url)));
}

function showJsonData($data, $pagesize = -1, $msg = NULL) {
  $result = _getJson(API_SUCCESS, $msg, NULL);
  $result['data'] = $data;
  $pagesize > 0 && $result['pagesize'] = $pagesize;

  header('Content-Type: application/json; charset=utf-8');
  die(jsonencode($result));
}

function showJsonResult($data, $msg = NULL, $code = API_SUCCESS) {
  $result = _getJson($code, $msg, NULL);
  $result['result'] = $data;

  header('Content-Type: application/json; charset=utf-8');
  die(jsonencode($result));
}

/**
 *
 * @param mixed $code 状态码
 * @param string $msg 提示信息
 * @param string $url 跳转链接
 * @return array (status、message、url 均为字符串)
 */
function _getJson($code, $msg = NULL, $url = NULL) {
  if (is_array($code)) {
    $msg = $code['message'];
    $code = $code['status'];
  }

  $result['status'] = strval(!empty($code) && strval($code)[0] === '9' ? $code : API_SUCCESS);

  if ($code === API_SUCCESS) $result['message'] = !isset($msg) || $msg === 'success' || $msg === '成功' ? '' : $msg;
  else $result['message'] = isset($msg) ? $msg : API_FAILURE_MSG;

  isset($url) && $result['url'] = $url;

  return $result;
}

function leftstr($string, $len) {
  return mb_substr($string, 0, $len);
}

function rightstr($string, $len) {
  return mb_substr($string, -$len);
}

function strstrex($source, $search, $before = FALSE, $include = FALSE) {
  $result = strstr($source, $search, $before);

  if ($result && !$before && !$include) $result = substr($result, strlen($search));

  return strval($result);
}

/**
 * 除指范围外,全替换为星号
 * @param string $input
 * @param integer $begin 保留前面几位
 * @param integer $end 保留后面几位
 * @return string
 */
function getMaskStr($input, $begin, $end) {
  if (!empty($input) && ($len = mb_strlen($input) - $begin - $end) > 0) {
    $result = mb_substr($input, 0, $begin) . str_repeat('*', $len) . mb_substr($input, -$end);
  }
  else $result = strval($input);

  return $result;
}

/**
 * 根据时间截计算年龄
 * @param integer $timestamp
 * @return integer
 */
function getCalAge($timestamp) {
  $timestamp = strval($timestamp);
  return isset($timestamp[4 + 3]) ? floor((time() - substr($timestamp, 0, -3)) / (86400 * 365)) : 0;
}

/**
 * 返回增加签名参数后的Get字符串
 * @param array $data 请求参数
 * @param string $salt
 * @return null|string
 */
function getSignQueryString(array $data, $salt = 'qianbao') {
  if ($data && ksort($data)) {
    $qs = http_build_query($data);
    $prefix = rtrim(config_item('variable_prefix'), '_-');

    return $qs . '&' . $prefix . 'sign=' . sha1($salt . $qs);
  }

  return;
}

/**
 * 判断GET参数的签名是否合法
 * @param type $salt
 * @return null|boolean
 */
function isSignQueryString($salt = 'qianbao') {
  if ($data = $_GET) {
    $prefix = rtrim(config_item('variable_prefix'), '_-') . 'sign';

    if (isset($data[$prefix]) && $sign = $data[$prefix]) {
      unset($data[$prefix]);

      return sha1($salt . http_build_query($data)) === $sign;
    }

    return FALSE;
  }

  return;
}

/**
 * 改变文件扩展名
 * @param string $filename
 * @param string $ext
 * @return string
 */
function changeExt($filename, $ext) {
  $i = strrpos($filename, '.');
  $filename = $i === FALSE ? $filename : substr($filename, 0, $i);
  return $filename . $ext;
}

function sha256($str) {
  return hash('sha256', $str);
}

/**
 * 格式化金额 千分逗号分隔
 * @param string|integer $input
 * @return string
 */
function formatMoney($input, $decimals = 2) {
  is_numeric($input) || $input = 0;
  if ($input > PHP_INT_MAX && is_string($input) && stripos($input, 'E') === FALSE) {
    sscanf($input, '%[^.].%s', $integer, $decimal);

    $result = '';
    $integer = strrev($integer);
    for ($i = strlen($integer) - 1; $i >= 0; $i--) {
      $result .= $integer[$i] . ($i % 3 === 0 && $i !== 0 ? ',' : '');
    }

    $decimal = strlen($decimal) < $decimals ? str_pad($decimal, $decimals, '0') :
      substr(sprintf("%.{$decimals}f", '.' . $decimal), 2);

    return $result . '.' . $decimal;
  }

  return number_format($input, $decimals);
}

function formatDate($input, $format = 'Y-m-d') { //strtotime('-7 day')  gmdate
  return date($format, strtotime($input));
}

function decodeUnicode($input) {
  return preg_replace_callback('/\\\\u(\w{4})/i',
    function ($match) {
    return iconv('UCS-2BE', 'UTF-8', pack('H4', $match[1]));
  }, $input);

  //return preg_replace('/\\\\u(\w{4})/ie', "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", $input);
}

/**
 * 包装 isset (可用于函数返回值)
 * @param mixed $data
 * @param string $key
 * @param string $value
 * @return boolean
 */
function isSetEx($data, $key = NULL, $value = NULL) {
  $result = isset($data);

  if ($result && isset($key)) {
    $key = explode('.', $key);
    foreach ($key as $item) {
      if ($result = isset($data[$item])) $data = $data[$item];
      else break;
    }
  }

  if ($result && isset($value)) {
    $result = $data === $value;
  }

  return $result;
}

/**
 * 判断输入的string是否为正整数 (不支持科学计数法)
 * @param string|integer $input
 * @param integer $min 最小值
 * @param integer $max 最大值
 * @return boolean
 */
function isUInt($input, $min = NULL, $max = NULL) {
  $result = ctype_digit(strval($input)); //PHP_INT_MAX
  $result && isset($min) && $result = $input >= $min;
  $result && isset($max) && $result = $input <= $max;
  //isset($default) && $result = $result ? intval($input) : intval($default);

  return $result;
}

/**
 * 判断输入的string是否为日期及指定格式
 * @param string $input
 * @param string $min
 * @param string $max
 * @param mixed $format
 * @return boolean
 */
function isDate($input, $min = NULL, $max = NULL, $format = ['Ymd', 'Y-m-d', 'Y/m/d', 'Y-n-j', 'Y/n/j']) {
  $temp = str_replace(['年', '月', '日'], ['-', '-'], $input);
  $result = ($temp = strtotime($temp)) !== FALSE;

  $result && isset($min) && $result = $temp >= (is_int($min) ? $min : strtotime($min));
  $result && isset($max) && $result = $temp <= (is_int($max) ? $max : strtotime($max));

  if ($result && isset($format)) {
    foreach ((array) $format as $item) {
      if (date($item, $temp) === $input) return TRUE;
    }

    $result = FALSE;
  }

  return $result;
}

function isUTF8($data, $encoding = 'utf-8') {
  return boolval(preg_match('//u', $data));

  return mb_check_encoding($data, $encoding);
}

/**
 * 根据 URI 返回 CSS 模块名
 * @param type $ci
 * @return string
 */
function getCssModule($uri = '') {
  if ($ci = getCiInstance()) {
    $uri || $uri = getRealUri(FALSE);

    $uri = explode('/', trim($uri, '/'));
    $method = $ci->router->method; //index

    sscanf(trim($ci->router->default_controller, '/'), '%[^/]/%[^/]', $class, $method);
    $method === end($uri) && array_pop($uri);
    count($uri) > 1 && $class === end($uri) && array_pop($uri);
  }

  return implode('_', $uri);
}

/**
 * 判断控制器是否存在
 * @param string $uri
 * @return boolean
 */
function isController($uri) {
  $class = 'home';
  $method = 'index';
  if (file_exists(APPPATH . 'config/routes.php')) {
    require APPPATH . 'config/routes.php';

    isset($route['default_controller']) && sscanf($route['default_controller'], '%[^/]/%s', $class, $method);
  }

  $segments = explode('/', trim($uri, '/'));
  empty($segments[0]) && $segments[0] = $class;
  empty($segments[1]) && $segments[1] = $method;

  $dir = NULL;
  $i = count($segments);
  while ($i-- > 0) {
    if (isFile($file = APPPATH . 'controllers/' . $dir . ucfirst($segments[0]) . '.php')) {
      $class = $segments[0];
      isset($segments[1]) && $method = $segments[1];

      if (preg_match('#(public)? function +' . $method . '\(#', file_get_contents($file))) return TRUE;

      break;
    }
    elseif (is_dir(APPPATH . 'controllers/' . $dir . $segments[0])) {
      $dir .= array_shift($segments) . '/';
    }
    else {
      $class = $segments[0];
      isset($segments[1]) && $method = $segments[1];

      break;
    }
  }

  return array('uri' => $uri, 'class' => $class, 'method' => $method, 'dir' => $dir);
}

/**
 * 判断文件是否存在，区分大小写
 * @param string $filename
 * @return boolean
 */
function isFile($filename) {
  if (is_file($filename)) {
    if (strstr(PHP_OS, 'WIN')) { //Darwin!!!
      return basename(realpath($filename)) === basename($filename);
    }

    return TRUE;
  }

  return FALSE;
}

function isAjax() {
  return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
    !empty($_SERVER['HTTP_ACCEPT']) && strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/json') !== FALSE;
}

function isEnv($env) {
  foreach (explode('|', $env) as $item) {
    switch (strtolower($item)) {
      case 'dev':
      case 'development':
        $result = ENVIRONMENT === 'development';

        break;

      case 'test':
      case 'testing':
        $result = ENVIRONMENT === 'testing';

        break;

      case 'pro':
      case 'production':
        $result = ENVIRONMENT === 'production';

        break;

      default:
        $result = FALSE;
    }

    if ($result) return TRUE;
  }

  return $result;
}

function isLocalhost() {
  return strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === 0;
}

function isApacheModule() {
  return PHP_SAPI === 'apache2handler';  //php_sapi_name  _SERVER['SERVER_SOFTWARE']
}

function iif($expr, $true, $false) {
  return $expr ? $true : $false;
}

function with($object) {
  return $object;
}

function jsonencode($data, $toObject = FALSE) {
  $option = (PHP_VERSION >= '5.4.0' ? JSON_UNESCAPED_UNICODE : 0) | ($toObject ? JSON_FORCE_OBJECT : 0);

  return is_array($data) ? json_encode($data, $option) : $data;
}

function jsondecode($data, $toObject = FALSE) { //JSON_BIGINT_AS_STRING
  $result = json_decode($data, !$toObject);

  if ($toObject) is_object($result) || $result = FALSE;
  else is_array($result) || $result = FALSE;

  return $result;
}

function DESEncrypt($data, $key) { //urlencode
  return openssl_encrypt($data, 'des-ecb', $key);
}

function DESDecrypt($data, $key) {
  return openssl_decrypt($data, 'des-ecb', $key);
}

function DES3Encrypt($data, $key) {
  return openssl_encrypt($data, 'des-ede3', $key); //OPENSSL_RAW_DATA
}

function DES3Decrypt($data, $key) {
  $result = openssl_decrypt($data, 'des-ede3', $key, OPENSSL_ZERO_PADDING);

  $padding = ord(substr($result, -1)); //DESede/ECB/ISO10126Padding
  $padding <= 8 && $result = substr($result, 0, -$padding);

  return $result;
}

function array_count(array $array, $recursive = FALSE) {
  return count($array, $recursive ? COUNT_RECURSIVE : COUNT_RECURSIVE);
}

function array_map_recursive(callable $callback, array $data) {
  return filter_var($data, FILTER_CALLBACK, ['options' => $callback]);

  $result = [];
  foreach ($data as $key => $item) {
    $result[$key] = is_array($item) ? array_map_recursive($callback, $item) : $callback($item);
  }

  return $result;
}

function array_change_case(array $data, $case = CASE_LOWER) {
  $result = [];
  foreach ($data as $key => &$item) {
    is_string($item) && $item = $case ? strtoupper($item) : strtolower($item);
    $result[$case ? strtoupper($key) : strtolower($key)] = $item;
  }

  return $result;
}

function array_change_value_case(array $data, $case = CASE_LOWER) {
  return array_map(function($item) use ($case) {
    is_string($item) && $item = $case ? strtoupper($item) : strtolower($item);

    return $item;
  }, $data);
}

function array_change_value_case_recursive(array $data, $case = CASE_LOWER) {
  return array_map(function($item) use ($case) {
    if (is_array($item)) $item = array_change_value_case_recursive($item, $case);
    elseif (is_string($item)) $item = $case ? strtoupper($item) : strtolower($item);

    return $item;
  }, $data);
}

function array_change_key_case_recursive(array $data, $case = CASE_LOWER) {
  return array_map(function($item) use ($case) {
    is_array($item) && $item = array_change_key_case_recursive($item, $case);

    return $item;
  }, array_change_key_case($data, $case));
}

function showTrace() {
  echo '<pre>';
  debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
  echo '</pre>';

  //$exc = new Exception;
  //return $exc->getTraceAsString();
}

function clearOPCache($filepath = FCPATH) { //opcache_reset
  if (ini_get('opcache.enable') == 1) {
    if (is_dir($filepath)) {
      $status = opcache_get_status();
      if (!empty($status['scripts'])) {
        foreach ($status['scripts'] as $item) {
          strpos($item['full_path'], $filepath) === 0 && opcache_invalidate($item['full_path'], TRUE);
        }
      }
    }
    elseif (is_file($filepath)) opcache_invalidate($filepath, TRUE);
  }
}
