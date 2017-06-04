<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/11/4
 * Time: 11:10
 */
class Realname_model extends Base_model {

  protected function _init() {
    parent::_init();
    $this->_host = HDB_URL_v2;
  }

  /*
   * 获取实名认证状态
   * */
  public function queryRealname() {
    $ret = $this->fetchPost('auth/queryRealname');
    return $ret;
  }

  /*
   * 实名认证
   * @param string idCard  身份证
   * @param string name    姓名
   * */
  public function realname($name, $idCard) {
    $param['idCard'] = $idCard;
    $param['name'] = $name;
    $ret = $this->fetchPost('auth/realname', $param);
    return $ret;
  }

  /*
   * 获取实名认证状态(发送验证码后 验证是否实名认证)
   * @param string mobilePhone  手机号
   * @param string randomCode   验证码
   * */
  public function getRealNameStatusByPhoneNum($mobilePhone, $randomCode) {
    $param['mobilePhone'] = $mobilePhone;

    try {
      checkForm('account/mobile', $param);
    } catch (Exception $e) {
      showJsonMsg(API_FAILURE, $e->getMessage());
    }

    $param['randomCode'] = $randomCode;

    $ret = $this->fetchPost('p/getRealNameStatusByPhoneNum', $param);
    return $ret;
  }
}