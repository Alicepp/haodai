<?php

defined('APPPATH') or die('Access restricted!');

require_once APPPATH . 'libraries/helperCurl.php';

class qb_Model extends CI_Model {

  //认证类型（读）
  const LOGIN_QUERY = 'url';
  const LOGIN_COOKIE = 'cookie';
  const LOGIN_HEADER = 'header';
  const LOGIN_SESSION = 'session';
  //会话类型（写）
  const SESSION_COOKIE = 'cookie';
  const SESSION_SESSION = 'session';

  private $_curl = NULL;
  //
  protected $_host = NULL; //API_URL
  protected $_prefix = NULL; //qbuid qbtoken
  protected $_methods = NULL;
  //
  protected $_filterStatus = NULL; //（非开发环境）
  //
  public $SIGN_UID = NULL;
  public $SIGN_TOKEN = NULL;

  final public function __construct() {
    parent::__construct();
    log_message('info', 'qb_Model Class Initialized');

    $this->_prefix = rtrim(config_item('variable_prefix'), '_-');
    $this->_methods = config_item('model_proxy_methods');

    $this->readLogin(self::LOGIN_COOKIE);

    $this->_init();
  }

  final public function __call($method, array $args) {
    static $ci = NULL;
    $ci || $ci = getCiInstance();

    if (empty($this->_methods) || in_array($method, $this->_methods)) {
      return call_user_func_array([$ci, $method], $args);
    }

    throw new InvalidArgumentException('No such or disabled method: ' . $method . '()');
  }

  protected function readLogin($loginType = self::LOGIN_COOKIE) {
    $uid = $this->_prefix . 'uid';
    $token = $this->_prefix . 'token';

    switch (strtolower($loginType)) {
      case self::LOGIN_QUERY:
        $this->SIGN_UID = $this->_get($uid);
        $this->SIGN_TOKEN = $this->_get($token);

        break;

      case self::LOGIN_HEADER:
        $this->SIGN_UID = $this->_server('HTTP_' . $uid);
        $this->SIGN_TOKEN = $this->_server('HTTP_' . $token);

        break;

      case self::LOGIN_SESSION:
        $this->SIGN_UID = $this->_getSession('login.' . $uid, '__SYSTEM');
        $this->SIGN_TOKEN = $this->_getSession('login.' . $token, '__SYSTEM');

        break;

      default: //cookie
        $this->SIGN_UID = $this->_getCookie($uid);
        $this->SIGN_TOKEN = $this->_getCookie($token);
    }
  }

  protected function writeLogin($uid, $token, $sessionType = self::SESSION_COOKIE, $time = 0) {
    switch (strtolower($sessionType)) {
      case self::SESSION_SESSION:
        $login[$this->_prefix . 'uid'] = $uid;
        $login[$this->_prefix . 'token'] = $token;

        $this->_setSession('login', $login, '__SYSTEM');

        break;

      default: //cookie
        $this->_setCookie($this->_prefix . 'uid', $uid, $time);
        $this->_setCookie($this->_prefix . 'token', $token, $time);
    }
  }

  protected function clearLogin($sessionType = self::SESSION_COOKIE) {
    switch (strtolower($sessionType)) {
      case self::SESSION_SESSION:
        $this->_setSession('login', NULL, '__SYSTEM');

        break;

      default: //cookie
        $this->_delCookie($this->_prefix . 'uid');
        $this->_delCookie($this->_prefix . 'token');
    }
  }

  /**
   * 批量请求
   * @param array $data [{'url':'', 'data':'', 'method':'GET'}]
   * @return array|false
   */
  public function fetchMulti(array $data = array()) {
    $url = RESTHUB_URL . '/rh/multicall?api_num=' . count($data);

    return $this->_fetchEx($url, $data, 'POST', TRUE);
  }

  public function fetchGet($url, $data = '') {
    return $this->_fetch($url, $data, 'GET');
  }

  public function fetchPost($url, $data = '') {
    return $this->_fetch($url, $data, 'POST');
  }

  private function _fetch($url, $data, $method) {
    return FETCH_DUMMY ? $this->_fetchDummy($url, $data) : $this->_fetchEx($url, $data, $method);
  }

  private function _initCurl($url) {
    isset($this->_curl) || $this->_curl = new helperCurl();

    if (substr($url, 0, 6) === 'https:') {
      $this->_curl->setOptions([CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0]);
    }
  }

  private function _parseUrl($url) {
    parse_url($url, PHP_URL_SCHEME) || ($this->_host && $url = rtrim($this->_host, '/') . '/' . ltrim($url, '/'));

    return $url;
  }

  /**
   * 获取接口信息
   * @param string $url 接口名称
   * @param string|array $data 接口参数
   * @param string $method 请求方式
   * @param boolean $multi 批量请求
   * @return array|false
   */
  private function _fetchEx($url, $data = '', $method = 'POST', $multi = FALSE) {
    $this->_initCurl($url = $this->_parseUrl($url));

    if ($multi) { //批量
      foreach ($data as &$item) {
        switch ($item['method'] = strtoupper($item['method'])) {
          case 'GET':
            $item['url'] .= (strpos($item['url'], '?') === FALSE ? '?' : '&')
              . (is_array($item['data']) ? http_build_query($item['data']) : $item['data']);

            $item['data'] = NULL;

            break;
        }

        $item['data'] = $this->fetchBefore($item['url'], $item['data'] ?: '');
      }

      $result = jsonencode($data);
    }
    else $result = $this->fetchBefore($url, $data ?: '');

    $time_begin = microtime(TRUE);
    switch (strtoupper($method)) {
      case 'GET':
        $result = $this->_curl->getJson($url, $result);

        break;

      case 'POST':
        $result = $this->_curl->postJson($url, $result);

        break;

      default:
        show_error('method error.');
    }
    $time_end = microtime(TRUE) - $time_begin;

    if ($result) {
      if ($multi) {
        foreach ($result['result'] as $key => &$item) {
          $item = $this->fetchAfter($data[$key]['url'], $item);
        }
      }
      else $result = $this->fetchAfter($url, $result);
    }

    $this->fetchLog($url, $data, $result, $time_end);

    if ($multi) {
      foreach ($result['result'] as &$item) {
        $item = $this->fetchFinish($item);
      }
    }
    else $result = $this->fetchFinish($result);

    return $result;
  }

  /**
   * 使用模拟数据返回 (忽略 fetchBefore、fetchAfter 方法)
   * @param string $url 接口名称
   * @param string|array $data 接口参数
   * @return array|false
   */
  private function _fetchDummy($url, $data = '') {
    $url = str_replace('/', '_', trim($url, '/')) . '.json';
    if (is_file($file = APPPATH . '../data/json/' . $url)) {
      $result = jsondecode(file_get_contents($file));

      is_integer(key($result)) && $result = $result[array_rand($result)];
    }
    else $result = _getJson(API_FAILURE, '接口文件不存在. [' . $url . ']');

    $log[] = __METHOD__ . ' [' . date('Y-m-d H:i:s') . ']';
    $log[] = '请求方式 ' . (isAjax() ? 'Ajax ' : '') . $_SERVER['REQUEST_METHOD'] . ' [' . ENVIRONMENT . ']';
    $log[] = '访问链接 ' . $_SERVER['REQUEST_URI'] . ' [' . $_SERVER['REMOTE_ADDR'] . ']';
    $log[] = '浏览器头 ' . $_SERVER['HTTP_USER_AGENT'];
    $log[] = '接口文件 ' . $url;
    $log[] = '请求参数 ' . jsonencode($data);
    $log[] = '模拟返回 ' . jsonencode($result);

    debugLog(implode(PHP_EOL, $log));

    return $this->fetchFinish($result);
  }

  public function setRequestHeader(array $header) {
    isset($this->_curl) || $this->_curl = new helperCurl();

    $this->_curl->setHeader($header);
  }

  public function setRequestProxy($url, $port = 8080) {
    isset($this->_curl) || $this->_curl = new helperCurl();

    $this->_curl->setProxy($url, $port);
  }

  /**
   * 设置忽略指定状态码的日志详情
   * @param string|integer $status
   */
  public function setFilterStatus($status = NULL) {
    $this->_filterStatus = $status;
  }

  protected function showJsonMsg($code, $msg = NULL, $url = '') {
    showJsonMsg($code, $msg, $url);
  }

  protected function showJsonData($data, $pagesize = -1, $msg = NULL) {
    showJsonData($data, $pagesize, $msg);
  }

  protected function showJsonResult($data, $msg = NULL) {
    showJsonResult($data, $msg);
  }

  /**
   * 写入请求日志
   * @param string $url
   * @param array $data
   * @param array|false $result
   * @param integer $time
   */
  protected function fetchLog($url, $data, $result, $time) {
    if (ENVIRONMENT !== 'development' && isset($this->_filterStatus, $result['status'], $result['result'])
      && $this->_filterStatus == $result['status']) $result['result'] = '【】';

    $log[] = __METHOD__ . ' [' . date('Y-m-d H:i:s') . ']';
    $log[] = '请求方式 ' . (isAjax() ? 'Ajax ' : '') . $_SERVER['REQUEST_METHOD'] . ' [' . ENVIRONMENT . ']';
    $log[] = '访问链接 ' . $_SERVER['REQUEST_URI'] . ' [' . $_SERVER['REMOTE_ADDR'] . ']';
    $log[] = '浏览器头 ' . $_SERVER['HTTP_USER_AGENT'];
    $log[] = '接口地址 ' . $url . (ENVIRONMENT === 'production' && !isLocalhost() ? '' :
      ' [' . gethostbyname(parse_url($url, PHP_URL_HOST)) . ']');
    $log[] = '头部参数 ' . jsonencode($this->_curl->getOption(CURLOPT_HTTPHEADER) ?: []);
    $log[] = '请求参数 ' . jsonencode($this->_filterLog($data)) . ' [' . $this->_curl->getOption(CURLOPT_CUSTOMREQUEST) . ']';
    $log[] = '响应时间 ' . number_format($time, 3) . ' sec';
    $log[] = '接口返回 ' . jsonencode($this->_filterLog($result));

    if (!$result) {
      $log[] = '';
      $log[] = '返回错误 ' . ($this->_curl->getError() ?: '未知错误 [' . $this->_curl->getCode() . ']');
      $log[] = '原始返回 ' . $this->_curl->getResonse();
    }

    debugLog(implode(PHP_EOL, $log));
  }

  /**
   * 根据配置文件，过滤指定字段数据（非开发环境）
   * @staticvar array $filters
   * @param array $data
   * @return array
   */
  protected function _filterLog($data) {
    if (ENVIRONMENT !== 'development' && is_array($data)) {
      static $filters = NULL;
      $filters || $filters = array_change_value_case(config_item('log_filter'));

      if ($filters) {
        foreach ($data as $key => $value) {
          if (is_array($value)) $data[$key] = $this->_filterLog($value);
          else if (in_array(strtolower($key), $filters)) $data[$key] = '【' . strlen($value) . '】';
        }
      }
    }

    return $data;
  }

  /**
   * 初始化操作，在构造方法最后执行
   */
  protected function _init() {
    //
  }

  /**
   * 请求前处理数据（加密或组装数据）
   * @param string $url
   * @param array|string $data
   * @return string|array
   */
  protected function fetchBefore($url, $data) {
    return $data;
  }

  /**
   * 请求成功后处理数据（处理解密数据，禁止在此处判断数据状态）
   * @param string $url
   * @param array|boolean $data
   * @return mixed
   */
  protected function fetchAfter($url, $data) {
    return $data;
  }

  /**
   * 请求完成后处理数据（判断数据状态或拼接数据）
   * @param array|boolean $data
   * @return mixed
   */
  protected function fetchFinish($data) {
    return $data;
  }

}
