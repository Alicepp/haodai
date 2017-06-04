<?php

class helperCurl {

  const JSON_ARRAY = 1;
  const JSON_OBJECT = 0;

  private $_options = [
    CURLOPT_TIMEOUT => 30,
    //CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_NOSIGNAL => 1, //libcurl 7.28.1
    CURLOPT_CONNECTTIMEOUT_MS => 300,
    //CURLOPT_HEADER => 1,
    CURLOPT_ENCODING => '', //CURLOPT_ACCEPT_ENCODING
    CURLOPT_FAILONERROR => 1,
    CURLOPT_RETURNTRANSFER => 1,
    //CURLOPT_AUTOREFERER => 1,
    CURLOPT_MAXREDIRS => 8,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, //IPv4
  ];
  //
  private $_curl;
  private $_error;
  private $_code;
  private $_headers; //response
  private $_response;

  static public function isLoadExt() {
    return extension_loaded('curl');
  }

  public function __construct(array $defaultOptions = []) {
    $this->_curl = curl_init();

    empty($defaultOptions) || $this->_options = $defaultOptions;
  }

  function __destruct() {
    curl_close($this->_curl);
  }

  public function getOption($key) {
    if (isset($this->_options[$key])) return $this->_options[$key];
    else return NULL;
  }

  public function setOption($key, $value) {
    $this->_options[$key] = $value;
  }

  public function setOptions(array $options) {
    $this->_options = array_replace($this->_options, $options);
  }

  public function setHeader(array $data) {
    //application/x-www-form-urlencoded  multipart/form-data
    foreach ($data as $key => $value) {
      $header[] = is_integer($key) ? $value : $key . ': ' . $value;
    }

    $this->setOption(CURLOPT_HTTPHEADER, $header); //CURLOPT_COOKIE
  }

  public function setProxy($url, $port = 8080) {
    $this->setOptions([CURLOPT_PROXY => $url, CURLOPT_PROXYPORT => $port]);
  }

  /**
   * 发送 GET 请求并返回解析后的 JSON 内容
   *
   * @param string $url
   * @param string|array $data
   * @param integer $return
   * @return mixed
   */
  public function getJson($url, $data = '', $return = self::JSON_ARRAY) {
    $content = json_decode($this->get($url, $data), $return);

    return $content ?: FALSE;
  }

  /**
   * 发送 GET 请求
   *
   * @param string $url
   * @param string|array $data
   * @return boolean|string
   */
  public function get($url, $data = '') {
    if ($data) {
      $url .= strpos($url, '?') === FALSE ? '?' : '&';
      $url .= is_array($data) ? http_build_query($data) : $data;
    }

    return $this->request([CURLOPT_URL => $url, CURLOPT_CUSTOMREQUEST => 'GET']);
  }

  /**
   * 发送 POST 请求并返回解析后的 JSON 内容
   *
   * @param string $url
   * @param string|array $data
   * @param integer $return
   * @return mixed
   */
  public function postJson($url, $data = '', $return = self::JSON_ARRAY) {
    $content = json_decode($this->post($url, $data), $return);

    return $content ?: FALSE;
  }

  /**
   * 发送 POST 请求
   *
   * @param string $url
   * @param string|array $data
   * @return boolean|string
   */
  public function post($url, $data = '') {
    if (is_array($data)) {
      $this->_options[CURLOPT_POSTFIELDS] = $this->isMultiPart($data) ? $data : http_build_query($data);
    }
    else $this->_options[CURLOPT_POSTFIELDS] = $data;

    return $this->request([CURLOPT_URL => $url, CURLOPT_CUSTOMREQUEST => 'POST']);
  }

  /**
   * 发送 cURL 请求
   *
   * @param array $options
   * @return boolean|string
   */
  private function request(array $options = []) {
    $this->_code = 0;
    $this->_error = '';
    $this->setOptions($options);
    curl_setopt_array($this->_curl, $this->_options);

    $this->_response = curl_exec($this->_curl);
    if ($this->_response === FALSE) {
      $this->_error = curl_error($this->_curl) . ' [' . curl_errno($this->_curl) . ']';
    }

    $headers = curl_getinfo($this->_curl); //CURLINFO_HEADER_SIZE
    isset($headers['http_code']) && $this->_code = $headers['http_code'];

    if (isset($this->_options[CURLOPT_HEADER]) && $this->_options[CURLOPT_HEADER] == 1) {
      $this->_headers = substr($this->_response, 0, $headers['header_size']);
      $this->_response = substr($this->_response, $headers['header_size']);
    }

    return $this->_response;
  }

  protected function isMultiPart(&$data) {
    $result = FALSE;
    foreach ($data as &$value) {
      if (isset($value[0]) && $value[0] === '@' && class_exists('CURLFile')) { //PHP5.6
        $filename = realpath(ltrim($value, '@'));
        $value = new CURLFile($filename);

        $result = TRUE;
      }
    }

    return $result;
  }

  private function gzipDecode($data) { //RFC 1952
    return strlen($data) < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b") ? $data : gzdecode($data);
  }

  public function getCode() {
    return $this->_code;
  }

  public function getResonse() {
    return $this->_response ?: '';
  }

  public function hasError() {
    return $this->_error !== NULL;
  }

  public function getError() {
    return $this->_error;
  }

  public function getLogs() {
    $url = $this->getOption(CURLOPT_URL);
    $data = $this->getOption(CURLOPT_POSTFIELDS);
    $response = $this->_response;

    $log[] = __METHOD__ . ' [' . date('Y-m-d H:i:s') . ']';
    $log[] = '请求方式 ' . (isAjax() ? 'Ajax ' : '') . $_SERVER['REQUEST_METHOD'] . ' [' . ENVIRONMENT . ']';
    $log[] = '访问链接 ' . $_SERVER['REQUEST_URI'] . ' [' . $_SERVER['REMOTE_ADDR'] . ']';
    $log[] = '浏览器头 ' . $_SERVER['HTTP_USER_AGENT'];
    $log[] = '接口地址 ' . $url . (ENVIRONMENT === 'production' && !isLocalhost() ? '' :
      ' [' . gethostbyname(parse_url($url, PHP_URL_HOST)) . ']');
    $log[] = '头部参数 ' . jsonencode($this->getOption(CURLOPT_HTTPHEADER) ?: []);
    $log[] = '请求参数 ' . $data . ' [' . $this->getOption(CURLOPT_CUSTOMREQUEST) . ']';
    $log[] = '接口返回 ' . $response;

    if (!$response) {
      $log[] = '';
      $log[] = '返回错误 ' . ($this->getError() ?: '未知错误 [' . $this->getCode() . ']');
      $log[] = '原始返回 ' . $this->getResonse();
    }

    return implode(PHP_EOL, $log);
  }

}
