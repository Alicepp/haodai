<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Day extends qb_Controller {

  public function _init() {
    parent::_init(); // TODO: Change the autogenerated stub
    $this->load->model('v2/Project_model', 'Project_model');
  }

  // 活期日息宝-转入转出说明
  public function faq() {
    $ret['title'] = '转入转出说明';
    $this->smarty->view('day/faq', $ret);
  }

  //活期日息宝账户数据
  public function currentdetail() {
    $data['title'] = '活期日息宝详情';
    $data['bid_info']['accountRemin'] = 0;
    $data['bid_info']['allinterest'] = 0;
    is_login() && $data['bid_info'] = _Super_queryPerCurrentBalance();
    $data['info'] = _Super_currentRxbDetail();

    $data['info']['yesterday'] = 0;
    $data['info']['sevenDayRate'] = sprintfNum($data['info']['sevenDayRate']*365);
    $data['info']['wanfensy'] = $data['info']['wanfensy'];
    $date = '';
    $rate = '';

    foreach ($data['info']['sevenDayList'] as $key => &$value) {
      $date .= date('m-d', $value['date']) . ',';
      $rate .= sprintfNum($value['sevenDayRate'] * 365) . ',';
    }

    $data['date']  = rtrim($date, ',');
    $data['rate']  = rtrim($rate, ',');

    $this->smarty->view('day/currentdetail', $data);
  }

  // 日息宝常见问题
  public function question() {
    $ret['title'] = '常见问题';
    $this->smarty->view('day/question', $ret);
  }
}