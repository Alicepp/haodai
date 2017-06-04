<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/11/2
 * Time: 14:18
 */
class Total_assets extends qb_Controller {

  //个人资产明细
  public function index() {
    $data['title'] = '总资产';
    $ret = $this->My_model->userAccountData();
    if (API_HDB_SUCCESS == $ret['status']) {
      $data['myinfo'] = $ret['result'];
      $this->smarty->view('my/total_assets', $data);
    } else {
      showError();
    }
  }
}