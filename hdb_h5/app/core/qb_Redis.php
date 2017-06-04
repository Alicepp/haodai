<?php

defined('APPPATH') or die('Access restricted!');

require_once APPPATH . 'libraries/helperCurl.php';

class qb_Redis {

  const SUCCESS = 200;

  protected $_host = NULL;
  protected $_prefix = NULL;

  public function __construct($host, $prefix = '') {
    // /redis/v1/health
    $this->_host = rtrim($host, '/') . '/';
    $this->_prefix = $prefix;
  }

  private function fetchPost($url, array $data = array()) {
    static $http = NULL;

    isset($http) || with($http = new helperCurl())->setHeader(['Content-Type' => 'application/json']);

    parse_url($url, PHP_URL_SCHEME) || ($url = $this->_host . ltrim($url, '/'));
    if (substr($url, 0, 6) === 'https:') {
      $http->setOptions([CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0]);
    }

    $data['namespace'] = $this->_prefix;
    $result = $http->postJson($url, jsonencode($data));

    debugLog($http->getLogs());

    return $result;
  }

  /**
   * 获取指定键的值 (不存在返回 NULL)
   * @param string $key
   * @return string|false
   */
  public function get($key) {
    $result = $this->fetchPost('/redis/v1/get', ['key' => $key]);

    if ($result['status'] === self::SUCCESS) return $result['result'];
    else { //类型错误
      errorLog(__METHOD__ . "\nSession Error: " . $result['message']);

      return FALSE;
    }
  }

  /**
   * 设置指定键的值 (键存在时,覆盖)
   * @param string $key
   * @param string $value
   * @return boolean
   */
  public function set($key, $value) {
    $result = $this->fetchPost('/redis/v1/set', ['key' => $key, 'value' => $value]);

    return $result['status'] === self::SUCCESS && $result['result'] === 'OK' ? TRUE : FALSE;
  }

  /**
   * 设置指定键的值,并设置生存周期 (秒)
   * @param string $key
   * @param integer $ttl
   * @param string $value
   * @return boolan
   */
  public function setex($key, $ttl, $value) {
    $result = $this->fetchPost('/redis/v1/setex', ['key' => $key, 'value' => $value, 'seconds' => $ttl]);

    return $result['status'] === self::SUCCESS && $result['result'] === 'OK' ? TRUE : FALSE;
  }

  /**
   * 设置指定键的值 (键不存在时)
   * @param string $key
   * @param string $value
   * @return boolean
   */
  public function setnx($key, $value) {
    $result = $this->fetchPost('/redis/v1/setnx', ['key' => $key, 'value' => $value]);

    return $result['status'] === self::SUCCESS && $result['result'] === 1 ? TRUE : FALSE;
  }

  /**
   * 删除指定的键
   * @param string $key
   * @return integer
   */
  public function delete($key) {
    $result = $this->fetchPost('/redis/v1/del', ['key' => $key]);

    return $result['result'];
  }

  /**
   * 设置键的生存周期 (秒)
   * @param string $key
   * @param integer $ttl
   * @return boolan
   */
  public function expire($key, $ttl) { //setTimeout
    $result = $this->fetchPost('/redis/v1/expire', ['key' => $key, 'seconds' => $ttl]);

    return $result['status'] === self::SUCCESS && $result['result'] === 1 ? TRUE : FALSE;
  }

  /**
   * 获取键的生存周期(秒)
   * @param string $key
   * @return integer|false
   */
  public function ttl($key) {
    $result = $this->fetchPost('/redis/v1/ttl', ['key' => $key]);

    if ($result['status'] === self::SUCCESS) {
      switch ($result['result']) {
        case -2: //不存在
          return -2;

        case -1: //未设置
          return -1;

        default:
          return $result['result'];
      }
    }
    else return FALSE;
  }

}
