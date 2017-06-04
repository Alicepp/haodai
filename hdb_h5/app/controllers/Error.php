<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/17
 * Time: 15:19
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends qb_Controller {

  // 出错页面
  public function index() {
    $data['title'] = '页面出错';
    $this->smarty->view('error/error_index', $data);
  }

  /*
   * token失效页面
   * */
  public function tokentimeout() {
    $ret['title'] = '请稍等';
    $my = _get_cache_userinfo();
    $urlPrefix = rtrim(config_item('variable_prefix'),'_').'url';
    $my && goRedirect(API_SUCCESS);
    $ret['backurl'] = $this->_get($urlPrefix);
    $ret['userPhone'] = $my['mobilePhone'];
    $ret['userinfo'] = $my;
    $ret['time'] = time();

    $this->smarty->view('error/tokentimeout', $ret);
  }
}