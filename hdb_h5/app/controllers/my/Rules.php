<?php
/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/18
 * Time: 11:30
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Rules extends qb_Controller {
  // 充值提现规则
  public function index() {
    $ret = $this->My_model->getSupportBankList();
    $view['title'] = '充值提现规则';
    $view['result'] = 0;
    if (API_HDB_SUCCESS == $ret['status']) {
      $view['result'] = $ret['result'];
      $this->smarty->view('my/rules', $view);
    } else {
      $this->smarty->view('my/rules', $view);
    }
  }
}