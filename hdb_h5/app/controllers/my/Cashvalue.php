<?php
/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/17
 * Time: 14:07
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Cashvalue extends qb_Controller {

  //充值
  public function recharge() {
    $data = _Super_getBindCardList('recharge');
    $data['banklist'] = _Super_getSupportBankList();
    $data['title'] = '充值';

    $this->smarty->view('my/cashvalue/recharge', $data);
  }

  //充值-操作
  public function do_recharge() {
    $Helper_ret = _Super_getBindCardList('do_recharge');

    $param['cardId'] = $Helper_ret['cardId'];
    $param['accountName'] = $Helper_ret['realName'];
    $param['bankCode'] = $Helper_ret['dkBankCode'];
    $param['bankName'] = $Helper_ret['bank'];
    $param['amount'] = $this->_post('amount');
    $ret = $this->My_model->quickPay($param);
    if (API_HDB_SUCCESS == $ret['status']) {
      $result['url'] = getRedirectStr('', '/bid/project');
      $result['money'] = formatMoney($param['amount']);
      showJsonResult($result, '充值成功');
    } else {
      showJsonMsg(API_FAILURE, $ret['message']);
    }
  }

  //充值成功
  public function recharge_success() {
    $data['title'] = '充值成功';
    $data['amount'] = $this->_getSession('recharge_amount');
    $this->smarty->view('my/cashvalue/recharge_success', $data);
  }

  //充值失败
  public function recharge_error() {
    $data['title'] = '充值失败';
    $this->smarty->view('my/cashvalue/recharge_error', $data);
  }

  //提现
  public function withdraw() {
    $data = _Super_getBindCardList();
    $data['title'] = '提现';
    $data['usableAmount'] = _get_cache_userinfo('usableAmount');
    $data['banklist'] = _Super_getSupportBankList();
    $this->smarty->view('my/cashvalue/withdraw', $data);
  }

  //提现手续费计算--由前端计算
  public function withdraw_brokerage() {
    $withdrawAmount = $this->_post('withdrawAmount');
    if (!isUInt($withdrawAmount)) {
      showJsonMsg(API_FAILURE, '提现金额异常');
    }

    $data = $this->My_model->getWithdrawFee($withdrawAmount);
    if (API_HDB_SUCCESS == $data['status']) {
      $this->_setSession('withdraw_amount', $withdrawAmount);
      showJsonResult($data['result']);
    }
    showJsonMsg(API_FAILURE);
  }

  //提现操作
  public function do_withdraw() {
    $data = _Super_getBindCardList();
    _Super_verificationTradePwd(false);

    $cardId = $data['cardId'];
    $withdrawAmount = $this->_post('withdrawAmount');
    $tradePwd = _DecryptPost('tradePwd');

    $ret = $this->My_model->withdraw($withdrawAmount, $tradePwd, $cardId);
    if (API_HDB_SUCCESS == $ret['status']) {
      $result['url'] = '/my/subsidiary/balance';
      $result['money'] = formatMoney($withdrawAmount);
      showJsonResult($result, '提现成功');
    } else {
      showJsonMsg(API_FAILURE, $ret['message']);
    }
  }

  //提现成功
  public function withdraw_success() {
    $data['title'] = '提现成功';
    $data['amount'] = $this->_getSession('withdraw_amount');
    $this->smarty->view('my/cashvalue/withdraw_success', $data);
  }

  //提现失败
  public function withdraw_error() {
    $data['title'] = '提现失败';
    $this->smarty->view('my/cashvalue/withdraw_error', $data);
  }

  //发卡银行
  public function banklist() {//该方法待删除
    $data['title'] = '发卡银行';
    $this->smarty->view('my/cashvalue/banklist', $data);
  }
}