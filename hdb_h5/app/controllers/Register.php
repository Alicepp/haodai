<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends qb_Controller {

  public function _init() {
    parent::_init();
    $this->router->method != 'agreement' && is_login() && common_redirect('home', false);
  }

  // 注册首页
  public function index() {
    $ret['title'] = ' 注册';
    $ret['send_code'] = '/register/do_identifyingCode';
    $ret['action_url'] = getRedirectStr('/register/do_register', true);
    getCodeImg();
    $this->smarty->view('register/register_index', $ret);
  }

  // 注册操作
  public function do_register() {
    verifyVerifyCode();
    $mobilePhone = $this->_post('mobilePhone');
    $pwd = _DecryptPost('pwd');
    $identifyingCode = $this->_post('identifyingCode');

    //第一步 验证图形验证码是否正确
    //第二步 验证手机号是否已存在
    //第三步 获取验证码-》用户点击获取验证码
    //第四步 请求注册接口
    _Super_phoneExists($mobilePhone, 'register_get_identifyingCode');
    $ret = $this->LoginRegister_model->register($mobilePhone, $pwd, $identifyingCode);
    if (API_HDB_SUCCESS == $ret['status']) {

      $result['url'] = '/my/realname/certification';
      showJsonResult($result, '注册成功');
    } else {
      goRedirect(API_FAILURE, '/register/register_error', '注册失败');
//      showJsonMsg(API_FAILURE, '注册失败', '/register/register_error');
    }

  }

  //获取注册验证码
  public function do_identifyingCode() {
    verifyVerifyCode();
    $mobilePhone = $this->_post('mobilePhone');
    _Super_phoneExists($mobilePhone, 'register_get_identifyingCode');
    $ret = send_code($mobilePhone);
    if (API_HDB_SUCCESS == $ret['status']) {
      showJsonMsg(API_SUCCESS);
    } else {
      showJsonMsg(API_FAILURE, $ret['message']);
    }
  }

  // 注册成功
  public function register_success() {
    $ret['title'] = ' 注册成功';
    $this->smarty->view('register/register_success', $ret);
  }

  // 注册失败
  public function register_error() {
    $ret['title'] = ' 注册失败';
    $this->smarty->view('register/register_error', $ret);
  }

  // 注册协议
  public function agreement() {
    $ret['title'] = ' 服务条款及软件许可';
    $this->smarty->view('register/agreement', $ret);
  }
}