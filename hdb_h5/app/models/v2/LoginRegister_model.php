<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class LoginRegister_model extends Base_model {

  protected function _init() {
    parent::_init();
    $this->_host = HDB_URL_v2;
  }

  /*
   * 登录
   * @param string Y userName 用户名
   * @param string Y passWord 密码
   *
   * 密码错误次数超限是必传
   * @param string N validCode 验证码
   * @param string N uuid 验证码与用户名对应的id
   * @return json
   * */
  public function login($userName, $passWord, $imageCode = '') {
    $param['userName'] = $userName;
    $param['passWord'] = $passWord;

    $param['validCode'] = $imageCode;
    $param['uuid'] = $this->_getSession('uuid', '_CODEIMG');//获取session中是否存在uuid 默认为空

    $ret = $this->fetchPost('p/login', $param);
    if (API_HDB_SUCCESS == $ret['status']) {
      $this->writeLogin($ret['result']['mid'], $ret['result']['token'], self::SESSION_COOKIE, 1800);

      goRedirect(API_SUCCESS, '', '', '/home/index');
    } else {
      $message = $ret['message'];
      if (4629 === $ret['status'] && !empty($ret['result']) && array_key_exists('image', $ret['result'])) {//密码错误次数超过限定的3次
        !empty($param['uuid']) && $message = '用户名密码或验证码错误';
        empty($imageCode) && $message = '';

        $this->_setSession('uuid', $ret['result']['uuid'], '_CODEIMG');
        $ret['result']['image'] = 'data:image/jpg;base64,' . $ret['result']['image'];

        unset($ret['result']['uuid']);

        showJsonResult($ret['result'], $message, API_PWD_FAILURE_OVERRUN);
      }
      showJsonMsg(API_FAILURE, $ret['message']);
    }

  }

  /*
   * 请求登录验证码
   * @param string mobilePhone 用户名
   *
   * */
  public function getValidCode($mobilePhone) {
    $param['userName'] = $mobilePhone;
    $ret = $this->fetchPost('p/getValidCode', $param);
    if (API_HDB_SUCCESS == $ret['status']) {
      showJsonMsg(API_SUCCESS, $ret['message']);
    }
    showJsonMsg(API_FAILURE, $ret['message']);
  }

  /*
   * 用户注册且登录
   * @param string mobilePhone  手机号
   * @param string pwd          密码
   * @param string randomCode   验证码
   * */
  public function register($mobilePhone, $pwd, $randomCode) {
    $param['mobilePhone'] = $mobilePhone;
    $param['pwd'] = $pwd;
    $param['msgCode'] = $randomCode;

    try {
      checkForm('account/register', $param);
    } catch (Exception $e) {
      showJsonMsg(API_FAILURE, $e->getMessage());
    }
    unset($param['msgCode']);

    $param['randomCode'] = $randomCode;

    $ret = $this->fetchPost('p/regist', $param);
    if (API_HDB_SUCCESS == $ret['status']) {
      $this->writeLogin($ret['result']['mid'], $ret['result']['token'], self::SESSION_COOKIE, 1800);
    }
    return $ret;
  }

  /*
   * 根据token验证是否登录
   * @param string token  token
   * */
  public function loginCheck() {
    $ret = $this->fetchPost('p/loginCheck');
    if (API_HDB_SUCCESS == $ret['status']) {
      return $ret;
    }
    return false;
  }

  /*
   * 用户退出
   * @param string token  token
   * */
  public function logout() {
    $ret = $this->fetchPost('p/logout');
    $this->_clearSession();
    $this->clearLogin(self::LOGIN_COOKIE);
    $ret['status'] == API_HDB_SUCCESS && redirect('/home');
  }

  /*
   * 查询用户锁定状态
   * 特殊编号2
   * */
  public function lockinfo() {
    $ret = $this->fetchPost('auth/member/lockinfo');
    return $ret;
  }

  /*
   * 查询用户手机号
   * 特殊编号2
   * */
  public function phone() {
    $ret = $this->fetchPost('auth/member/phone');
    return $ret;
  }

  /*
   * 手机号注册时检测手机号是否已经存在
   * @param string mobilePhone             手机号
   * */
  public function phoneExists($mobilePhone) {
    $param['mobilePhone'] = $mobilePhone;
    try {
      checkForm('account/mobile', $param);
    } catch (Exception $e) {
      showJsonMsg(API_FAILURE, $e->getMessage());
    }
    $ret = $this->fetchPost('p/phoneExists', $param);
    return $ret;
  }


}
