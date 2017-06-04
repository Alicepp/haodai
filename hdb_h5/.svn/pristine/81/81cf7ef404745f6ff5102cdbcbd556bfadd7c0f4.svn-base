<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/19
 * Time: 18:54
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Other_model extends Base_model {

  protected function _init() {
    parent::_init();
    $this->_host = HDB_URL_v2;
  }

  /*
   * 发送手机验证码
   * @param string phoneNum      手机号
   * */
  public function getPhoneVerificationCode($phoneNum) {

    try {
      checkForm('account/mobile', array('mobilePhone' => $phoneNum));
    } catch (Exception $e) {
      showJsonMsg(API_FAILURE, $e->getMessage());
    }

    $param['phoneNum'] = $phoneNum;

    $ret = $this->fetchPost('p/member/getPhoneVerificationCode', $param);
    return $ret;
  }

  /*
   * http://192.168.1.149/index.php?s=/18&page_id=376
   * 获取随机验证码图片（张超）
   * */
  public function getValidateCodeImage($uuid = '') {
    $param['uuid'] = $uuid;
    $ret = $this->fetchPost('p/login/getValidateCodeImage', $param);
    return $ret;
  }

  /*
   * http://192.168.1.149/index.php?s=/18&page_id=377
   * 验证登录验证码是否正确（张超）
   * @param uuid          Y string 验证码唯一标示
   * @param validateCode  Y string 验证码
   * */
  public function checkValidateCode($uuid = '', $code = '') {
    $param['uuid'] = $uuid;
    $param['validateCode'] = $code;
    $ret = $this->fetchPost('p/login/checkValidateCode');
    return $ret;
  }

}