<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/31
 * Time: 18:03
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Automatic_bid extends qb_Controller {

  //自动投标说明
  public function explain() {
    $data['title'] = '自动投标说明';
    $this->smarty->view('automatic_bid/explain', $data);
  }

  //自动投标协议
  public function agreement() {
    $data['title'] = '自动投标协议';
    $this->smarty->view('automatic_bid/agreement', $data);
  }
}