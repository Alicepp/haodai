<?php
/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/17
 * Time: 15:04
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Subsidiary extends qb_Controller {

  //收支明细
  public function balance() {
    $this->smarty->assign('title', '收支明细');
    $this->smarty->assign('page_url', '/my/subsidiary/balance');
    if (isAjax()) {
      $pageIndex = $this->_post('pageIndex');
      $queryType = $this->_post('type');

      $ret = $this->My_model->balanceOfPayments($pageIndex, $pagesize = 15, $queryType);

      $key = ['balance','outlay'];
      update_array_value($ret, $key);

      $data = array();
      if (API_HDB_SUCCESS == $ret['status'] && 0 != $ret['result']['count']) {
        $data = $ret['result']['rechargeList'];
      }
      showJsonData($data, $pagesize);
    }
    $this->smarty->view('my/subsidiary/balance');
  }

  //投标记录
  public function bids_records() {
    $this->smarty->assign('title', '投标记录');
    $this->smarty->assign('page_url', '/my/subsidiary/bids_records');
    if (isAjax()) {
      $pageIndex = $this->_post('pageIndex');
      $bidType = $this->_post('type');
      $status = 0;
      $ret = $this->My_model->bidInvestRecordsDivide($pageIndex, $pagesize = 15, $status, $bidType);

      $data = array();
      if (API_HDB_SUCCESS == $ret['status'] && 0 != $ret['result']['count']) {
        $key = ['amount','accountRemin'];
        update_array_value($ret, $key);
        $data = $ret['result']['memberInvestList'];
      }
      showJsonData($data, $pagesize);
    } else {
      $this->smarty->view('my/subsidiary/bids_records');
    }
  }

  //回款记录
  public function payment_records() {
    $this->smarty->assign('title', '回款记录');
    $this->smarty->assign('page_url', '/my/subsidiary/payment_records');

    if (isAjax()) {
      $pageIndex = $this->_post('pageIndex');
      $type = $this->_post('type');
      $data = array();
      $ret = $this->My_model->getMemberRefundList($pageIndex, $pagesize = 15, $type);
      if (API_HDB_SUCCESS == $ret['status'] && 0 != $ret['result']['count']) {
        $data = $ret['result']['memberRefundList'];
      }
      showJsonData($data, $pagesize);
    } else {
      $this->smarty->view('my/subsidiary/payment_records');
    }
  }
}