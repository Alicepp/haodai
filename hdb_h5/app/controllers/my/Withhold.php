<?php
/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/18
 * Time: 11:29
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Withhold extends qb_Controller {
  // 代扣协议
  public function index() {
    $data['title'] = '代扣协议';
    $this->smarty->view('my/withhold', $data);
  }
}