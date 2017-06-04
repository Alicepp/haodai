<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/18
 * Time: 17:51
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends qb_Controller {

  public function _init() {
    parent::_init(); // TODO: Change the autogenerated stub
  }

  /*
   * 系统公告
   * */
  public function index($type = '') {
    $data['title'] = '消息';
    $this->smarty->view('notice/index', $data);
  }

  //获取系统公告列表
  public function noticelist() {
    $pageIndex = $this->_post('pageIndex');
    $data['result'] = array();
    $data['result'] = $this->Qbao_model->getSystemMessageList($pageIndex, 15);
    showJsonData($data['result'], 15);
  }

  //个人消息中心
  public function usermsglist() {
    $pageIndex = $this->_post('pageIndex');
    $data['result'] = array();
    if (is_login()) {
      $usermsg = $this->My_model->getMySysMsg($pageIndex, 15);
      if (API_HDB_SUCCESS == $usermsg['status']) {
        $data['result'] = $usermsg['result']['msgList'];
        showJsonData($data['result'], 15);
      }
    }
    showJsonData($data['result'], 15);
  }

  /*
   * 公告详情
   * */
  public function detail($messageId = 0) {
    $data['title'] = '公告详情';
    $result = $this->Qbao_model->getOneSystemMessage($messageId);
    $data['result'] = array();
    if (API_QB_SUCCESS == $result['status']) {
      $data['result'] = $result['result'];
    }

    $this->smarty->view('notice/detail', $data);
  }

  /*
   * 消息置为已读
   * */
  public function readSysMsg($messageId = 0) {
    $ret = $this->My_model->readSysMsg($messageId);
    if (API_HDB_SUCCESS == $ret['status']) {
      showJsonMsg(API_SUCCESS);
    } else {
      showJsonMsg(API_FAILURE);
    }
  }
}