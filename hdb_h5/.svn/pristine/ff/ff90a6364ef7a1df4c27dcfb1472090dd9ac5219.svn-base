<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/11/4
 * Time: 11:55
 */
class Password_model extends Base_model {

  protected function _init() {
    parent::_init();
    $this->_host = HDB_URL_v2;
  }

  /*
   * 设置登录密码
   * @param string loginPwd     登录密码
   * @param String mobilePhone   手机号
   * @param String randomCode   手机验证码
   * */
  public function initLoginPwd($mobilePhone, $loginPwd, $randomCode) {
    $param['mobilePhone'] = $mobilePhone;
    $param['loginPwd'] = $loginPwd;
    $param['randomCode'] = $randomCode;

    try {
      checkForm('account/mobile', $param);
    } catch (Exception $e) {
      showJsonMsg(API_FAILURE, $e->getMessage());
    }

    $ret = $this->fetchPost('p/initLoginPwd', $param);
    return $ret;
  }


  /*
   * 是否设置交易密码
   * return array|bool
   * */
  public function verificationTradePwd() {
    $ret = $this->fetchPost('auth/verificationTradePwd');
    if (API_HDB_SUCCESS == $ret['status']) {
      return $ret['result']['status'];
    } else {
      return false;
    }
  }

  /*
   * 设置交易密码
   * @param string setPwdType 设置密码类型
   *      1：已设置交易密码，忘记交易密码时用
   *      2：未设置交易密码，活期日息宝转入提示设置交易密码时用
   * @param string tradePwd     交易密码
   * @param String mobilePhone  手机号 (setPwdType为2时不填)
   * @param String randomCode   手机验证码 (setPwdType为2时不填)
   * */
  public function initTradePwd($mobilePhone, $tradePwd, $randomCode = '', $setPwdType) {
    $param['mobilePhone'] = $mobilePhone;

    try {
      $checkParam['passWord'] = $tradePwd;
      checkForm('account/transaction/pwd', $checkParam);
      checkForm('account/mobile', $param);
    } catch (Exception $e) {
      showJsonMsg(API_FAILURE, $e->getMessage());
    }

    $param['tradePwd'] = $tradePwd;
    $param['randomCode'] = $randomCode;
    $param['setPwdType'] = $setPwdType;
    if (2 == $setPwdType) {
      unset($param['randomCode'], $param['mobilePhone']);
    }

    $ret = $this->fetchPost('p/initTradePwd', $param);
    return $ret;
  }

  /*
   * 修改交易密码
   * @param string tradePwd             原交易密码
   * @param string newTradePwd          新交易密码
   * @param string newTradePwdRepeat    确认新交易密码
   * */
  public function modifyTradePwd($tradePwd, $newTradePwd, $newTradePwdRepeat) {
    $param['tradePwd'] = $tradePwd;
    $param['newTradePwd'] = $newTradePwd;
    $param['newTradePwdRepeat'] = $newTradePwdRepeat;

    try {
      $checkParam['passWord'] = $newTradePwd;
      checkForm('account/transaction/pwd', $checkParam);
    } catch (Exception $e) {
      showJsonMsg(API_FAILURE, $e->getMessage());
    }

    $ret = $this->fetchPost('auth/member/modifyTradePwd', $param);
    return $ret;
  }

  /*
   * 修改登录密码
   * @param string loginPwd             原登录密码
   * @param string newLoginPwd          新登录密码
   * @param string newLoginPwdRepeat    确认新登录密码
   * */
  public function modifyLoginPwd($loginPwd, $newLoginPwd, $newLoginPwdRepeat) {
    $param['loginPwd'] = $loginPwd;
    $param['newLoginPwd'] = $newLoginPwd;
    $param['newLoginPwdRepeat'] = $newLoginPwdRepeat;

    try {
      checkForm('account/login/pwd', array('passWord' => $param['newLoginPwdRepeat']));
    } catch (Exception $e) {
      showJsonMsg(API_FAILURE, $e->getMessage());
    }

    $ret = $this->fetchPost('auth/member/modifyLoginPwd', $param);
    return $ret;
  }
}