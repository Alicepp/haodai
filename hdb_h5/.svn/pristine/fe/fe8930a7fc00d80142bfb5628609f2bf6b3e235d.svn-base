<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/11/15
 * Time: 21:32
 */
class Common extends qb_Controller {

  /*
   * 发送验证码
   * */
  public function send_code($source = '') {
    if (!empty($source)) {
      verifyVerifyCode();
    }
    $mobilePhone = $this->_post('mobilePhone');
    $ret = send_code($mobilePhone);
    if (API_HDB_SUCCESS == $ret['status']) {
      showJsonMsg(API_SUCCESS);
    } else {
      showJsonMsg(API_FAILURE, $ret['message']);
    }
  }

  /*
   * 获取图形验证码并赋值到smarty模板
   * */
  public function getCodeImg($source = '') {
    getCodeImg($source);
  }

  /*
   * 是否设置交易密码
   * */
  public function common_verificationTradePwd() {
    _Super_verificationTradePwd();
  }

  /*
   * 隐藏下载app的提示
   * */
  public function hideDownload() {
    setHideDownloadCookieKey();
    showJsonMsg(API_SUCCESS);
  }

}