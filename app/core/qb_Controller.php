<?php

defined('APPPATH') or die('Access restricted!');

class qb_Controller extends CI_Controller {

  private $_cookies = NULL;

  final public function __construct() {
    parent::__construct();
    log_message('info', 'qb_Controller Class Initialized');

    $this->_init();
  }

  final public function __get($name) {
    static $lazy;

    if (is_null($lazy)) {
      if (file_exists($file = APPPATH . 'config/lazy_init.php')) require $file;
      else $lazy = [];
    }

    $value = NULL;
    if (isset($lazy[$name]) && is_callable($lazy[$name])) {
      $value = $lazy[$name]();

      $this->$name = $value;
    }
    else trigger_error('Undefined property: ' . get_called_class() . '::$' . $name);

    return $value;
  }

  public function _get($field) {
    return trim($this->input->get($field));
  }

  public function _post($field) {
    return trim($this->input->post($field));
  }

  public function _server($field) {
    return $this->input->server(strtoupper($field));
  }

  public function _getCookie($key, $field = NULL) {
    //$prefix = config_item('cookie_prefix');

    $result = isset($this->_cookies[$key]) ? $this->_cookies[$key] : NULL;
    if ($result || $result = $this->input->cookie(str_replace('.', '_', $key))) {
      if (is_string($result) && $result[0] === '{' && ($result = jsondecode($result)) === FALSE) return;

      if (isset($field) && $result) {
        foreach (explode('.', $field) as $item) {
          if (isset($result[$item])) $result = $result[$item];
          else return NULL;
        }
      }
    }

    return $result;
  }

  public function _setCookie($key, $value = '', $expire = 0, $httponly = TRUE, $domain = '', $path = '/') {
    $this->_cookies[$key] = $value;

    $this->input->set_cookie($key, jsonencode($value), $expire, $domain, $path, '',
      ENVIRONMENT === 'production' && is_https() ? TRUE : FALSE, $httponly);
  }

  public function _delCookie($key, $domain = '', $path = '/') {
    $this->_setCookie($key, '', '', FALSE, $domain, $path);
  }

  /**
   * 初始化 Session (永远不要直接调用原生 session_start 函数)
   */
  private function _initSession() {
    isset($this->session) || $this->load->library('session');

    session_status() === PHP_SESSION_NONE && session_start();
  }

  /**
   * 返回 Session 数据，不存在返回 NULL
   * @param string $key 支持直接多级键值读取 [key.key...N]
   * @param string $scope
   * @return mixed
   */
  public function _getSession($key = NULL, $scope = NULL) {
    $this->_initSession();
    $scope = empty($scope) ? static::class : $scope;

    $result = isset($_SESSION[$scope]) ? $_SESSION[$scope] : NULL;
    if ($result && isset($key)) {
      foreach (explode('.', $key) as $item) {
        if (isset($result[$item])) $result = $result[$item];
        else return NULL;
      }
    }

    return $result;
  }

  public function _setSession($key, $value = NULL, $scope = NULL) {
    $this->_initSession();
    $scope = empty($scope) ? static::class : $scope;

    if (is_null($value)) unset($_SESSION[$scope][$key]);
    else $_SESSION[$scope][$key] = $value;
  }

  public function _clearSession() {
    $this->_initSession();

    session_unset(); //session_destroy
  }

  public function _closeSession() {
    session_write_close(); //session_commit
  }

  /**
   * 返回状态值是否过期
   * @param string $key 标识字符
   * @param integer $time 间隔时间
   * @return boolean|null
   */
  public function isStateExpire($key, $time = NULL) {
    $keytime = $this->_getSession($key, 'SYS_STATE_EXPIRE');
    if (is_integer($time)) return empty($keytime) ? NULL : $keytime + $time < time();
    else $this->_setSession($key, time(), 'SYS_STATE_EXPIRE');
  }

  /**
   * 初始化操作，在构造方法最后执行
   */
  protected function _init() {
    //
  }

}
