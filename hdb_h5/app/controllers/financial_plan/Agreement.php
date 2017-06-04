<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/12/26
 * Time: 15:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Agreement extends qb_Controller {

  /*
   * 理财计划协议
   * */
  public function service() {
    $data['title'] = '理财计划协议';
    $this->smarty->view('financial_plan/agreement/service',$data);
  }
}