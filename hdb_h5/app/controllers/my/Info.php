<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends qb_Controller {

  // 我的首页
  public function index() {
    $data['title'] = '我的';
    $data['myinfo']['msgUnReadCount'] =0;
    if (is_login()) {//已登录
      $data['myinfo'] = _get_cache_userinfo();
      $msg = $this->My_model->getMySysMsg();
      _Super_queryRealname('smarty'); //实名认证
      $data['myinfo']['msgUnReadCount'] = $msg['result']['msgUnReadCount'];
    }

    $data['login_url'] = getRedirectStr('/login/index', true);
    $this->smarty->view('my/info/info_home', $data);
  }
}
