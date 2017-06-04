<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Base_model extends qb_Model {

  /**
   * 初始化操作，在构造方法最后执行
   */
  public $version = 320;

  protected function _init() {
    setHeader();
    $this->version = $this->_server('http_version');
  }

  public function fetch($url, array $data = array()) {
    return $this->fetchPost($url, $data);
  }

  /*
   * 请求之前 设置header头
   * */

  protected function fetchBefore($url, $data) {
    $send_header = send_header();
    //兼容app版本---3.1.0之前版本token是通过header传输的
    $token = 'token:' . $this->_server('http_' . $this->_prefix . '_token');
    $token != 'token:' && empty($this->SIGN_TOKEN) && $this->writeLogin('', $token, self::SESSION_COOKIE, 1800);

    $token == 'token:' && $token = 'token: ' . $this->SIGN_TOKEN;
    array_push($send_header, $token);
    $this->setRequestHeader($send_header);

    return jsonencode(['content' => empty($data) ? '' : jsonencode($data)]);
  }

  protected function fetchFinish($data) {
    empty($data) && showError();
    switch ($data['status']) {
      case 502://服务器异常
      case 404://接口地址没有请求到
        showError();
        break;
      case 4521://验证码不正确或者已经失效
      case 4610://活期转出失败
        showJsonMsg(API_FAILURE, $data['message']);
        break;
      case 4531://交易密码错误
        showJsonMsg(API_FAILURE, '请输入正确的交易密码');
        break;
      case 4611://交易密码错误
        showJsonMsg(API_FAILURE, '转出金额必须是1元以上且小于1000000整数');
        break;
    }

    return $data;
  }

}
