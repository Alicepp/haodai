<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . "models/Base_model.php");

class Api_model extends Base_model {
  protected function _init() {
    parent::_init();
    $this->_host = HDB_URL_v1;
  }

  protected function fetchBefore($url, $data) {
    $cookie = !empty($this->_server('http_hdb_cookie')) ? $this->_server('http_hdb_cookie') : null;
    $cookie || $cookie = !empty($_GET['cookie']) ? $_GET['cookie'] : null;
    $header = [
      'Content-Type: application/json; charset=utf-8',
      'cookie:' . $cookie
    ];

    $this->setRequestHeader($header);

    return jsonencode($data);
  }

  protected function fetchFinish($data) {
    $data = parent::fetchFinish($data);
    if (!$data) {
      common_redirect('error/index', false);
    }
    return $data;
  }

  // 获取银行卡列表
  public function getRules() {
    return $this->fetch('supportBank/bankList');
  }

  // 实名认证
  public function getInfo() {
    return $this->fetch('realName/getInfo');
  }

  // 获取用户信息
  public function getUserInfo() {
    return $this->fetch('userInfo/main');
  }

  // 获取更多详情
  // 在下一个版本中舍弃该方法
  public function getMoreDetail($id) {
    return $this->fetchGet('project/' . $id . '/moreDetail');
  }

  // 获取用户信息
  public function getCheckLogin() {
    if (empty($this->header['hdb_token'])) common_redirect('error/tokentimeout');

    $ret = $this->fetch('userInfo/checkLogin');
    if (API_HDB_SUCCESS != $ret['infoData']['errorCode']) {
      common_redirect('login/index');
    }

    cache_session_logininfo($ret);
    return $ret;
  }

}
