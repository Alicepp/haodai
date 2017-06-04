<?php
/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/17
 * Time: 14:52
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Realname extends qb_Controller {

  //实名认证
  public function certification() {
    $data['title'] = '实名认证';
    $data['action_url'] = getRedirectStr('/my/realname/do_realname', true);
    $this->smarty->view('/my/realname/certification', $data);
  }

  public function do_Realname() {
    $name = $this->_post('name');
    $idCard = $this->_post('idCard');
    if (!validation_filter_id_card($idCard)) {
      showJsonMsg(API_FAILURE, '身份证号码异常,请重新输入');
    }
    $ret = $this->Realname_model->realname($name, $idCard);
    if (API_HDB_SUCCESS == $ret['status']) {
      $result['url'] = '/my/realname/bindcard';
      showJsonResult($result, '恭喜您实名认证成功！');
    }
    showJsonMsg(API_FAILURE, $ret['message']);
  }

  //绑卡
  public function bindcard() {
    _Super_getBindCardList('bindcard');
    $data['banklist'] = _Super_getSupportBankList();
    $data['title'] = '添加银行卡';
    $data['action_url'] = getRedirectStr('/my/realname/do_bindcard', true);

    $this->smarty->view('/my/realname/bindcard', $data);
  }

  //绑卡操作--获取银行验证码--快捷签约
  public function send_signBank_code() {
    $data['mobilePhone'] = $this->_post('mobilePhone');
    $data['bankCardNo'] = str_replace(' ', '', $this->_post('cardNo'));
    $data['bankCode'] = $this->_post('bankCode');
    $data['bankName'] = $this->_post('bankName');
    $data['amount'] = $this->_post('amount');

    $ret = $this->My_model->quickPaySign($data);
    if (API_HDB_SUCCESS == $ret['status']) {
      $data['sign'] = $ret['result']['sign'];
      $this->_setSession('signBank', $data, '__TEMP');
      showJsonMsg(API_SUCCESS, $ret['message']);
    } else {
      showJsonMsg(API_FAILURE, $ret['message']);
    }
  }

  public function do_bindcard() {
    $data = $this->_getSession('signBank', '__TEMP');
    $data['verifyCode'] = $this->_post('securityCode');
    $ret = $this->My_model->quickPay($data);
    if (API_HDB_SUCCESS == $ret['status']) {
      $result['url'] = '/my/cashvalue/recharge';
      showJsonResult($result, '恭喜您成功绑定银行卡!');
    } else {
      showJsonMsg(API_FAILURE, $ret['message']);
    }
  }

  //获取用户银行卡
  public function bankcard() {
    $data['title'] = '银行卡';
    $data['result'] = _Super_getBindCardList('bankcard');
    $this->smarty->view('/my/realname/bankcard', $data);
  }

  /*
   * 获取银行卡列表
   * */
  public function getbankcardList() {
    $ret = $this->My_model->getSupportBankList();
    $data['result'] = array();
    if (API_HDB_SUCCESS == $ret['status']) {
      showJsonData($ret['result']);
    }
    showJsonMsg(API_FAILURE);
  }

  //实名认证成功
  public function real_name_success() {
    $data['title'] = '实名认证成功';
    $this->smarty->view('my/realname/real_name_success', $data);
  }

  //实名认证失败
  public function real_name_error() {
    $data['title'] = '实名认证失败';
    $this->smarty->view('my/realname/real_name_error', $data);
  }

  //绑卡成功
  public function tied_card_success() {
    $data['title'] = '绑卡成功';
    $this->smarty->view('my/realname/tied_card_success', $data);
  }

  //绑卡失败
  public function tied_card_error() {
    $data['title'] = '绑卡失败';
    $this->smarty->view('my/realname/tied_card_error', $data);
  }

}