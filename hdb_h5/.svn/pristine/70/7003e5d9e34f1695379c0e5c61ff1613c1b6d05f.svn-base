<?php
/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/18
 * Time: 18:52
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends qb_Controller {

  public function index() {
    $data['title'] = '意见反馈';
    $data['action_url'] = '/my/feedback/do_content';
    getCodeImg();
    $this->smarty->view('my/feedback', $data);
  }

  public function do_content() {
    verifyVerifyCode();
    $mobilePhone = _get_cache_userinfo('mobilePhone');
    $content = $this->_post('content');
    $ret = $this->Qbao_model->submitSuggestion($mobilePhone, $content);
    if ($ret && API_QB_SUCCESS == $ret['status']) {
      showJsonMsg(API_SUCCESS, '您的反馈提交成功', '/home/more');
    } else {
      showJsonMsg(API_FAILURE, '网络异常导致提交失败,请稍后再试');
    }
  }
}