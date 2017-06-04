<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends Base_model {

  protected function _init() {
    parent::_init();
    $this->_host = HDB_URL_v2;
  }

  protected function fetchFinish($data) {
    $data = parent::fetchFinish($data);
    return update_array_value($data, ['approvedPeriod']);
  }

  /*
   * 首页
   * */
  public function index() {
    $ret = $this->fetchPost('p/h5/index');
    return $ret;
  }
}
