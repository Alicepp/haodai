<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/17
 * Time: 15:56
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends qb_Controller {

  public function _init() {
    parent::_init();
    $this->router->method != 'do_loginOff' && is_login() && common_redirect('home', false);
  }

  // 登录
  public function index() {
    $data['title'] = '登录';
    $this->smarty->view('login/login_index', $data);
  }

  // 登录操作
  public function do_login() {
    $userName = $this->_post('mobilePhone');
    $passWord = _DecryptPost('pwd');
    $imageCode = $this->_post('identifyCode');
    $this->LoginRegister_model->login($userName, $passWord, $imageCode);
  }

  // 退出登录
  public function do_loginOff() {
    $this->LoginRegister_model->logout();
  }


  // 忘记密码
  public function forget() {
    $ret['title'] = ' 忘记登录密码';
    getCodeImg();
    $this->smarty->view('login/forget', $ret);
  }


  // 第一步-忘记密码-获取验证码
  public function do_forget_one() {
    verifyVerifyCode();
    $mobilePhone = $this->_post('mobilePhone');
    _Super_phoneExists($mobilePhone, 'forgetPwd_get_identifyingCode');

    $mobilePhone = $this->_post('mobilePhone');
    $ret = send_code($mobilePhone, true);
    if (API_HDB_SUCCESS == $ret['status']) {
      showJsonMsg(API_SUCCESS);
    } else {
      showJsonMsg(API_FAILURE, $ret['message']);
    }
  }

  /*
   * 第二步-忘记密码-验证是否实名认证
   * 1.未实名{直接设置密码}
   * 2.已实名{验证姓名和身份证号}
   * */
  public function do_forget_two() {
    $mobilePhone = $this->_post('mobilePhone');
    $randomCode = $this->_post('randomCode');
    $ret = $this->Realname_model->getRealNameStatusByPhoneNum($mobilePhone, $randomCode);
    $show['is_realname'] = 0;
    if (API_HDB_SUCCESS == $ret['status']) {
      $temp['mobilephone'] = $mobilePhone;
      $temp['code'] = $randomCode;
      if (1 == $ret['result']['status']) {//已实名
        $temp['name'] = $ret['result']['realName'];
        $temp['idCard'] = $ret['result']['idCard'];

        $show['is_realname'] = 1;
      }
      $this->_setSession('temp', $temp, '__TEMP');
      showJsonResult($show);
    }
    showJsonMsg(API_FAILURE, $ret['message']);
  }

  /*
   * 实名认证
   * */
  public function Realname_auth() {
    $post_name_idCard = $this->_post('idCard') . $this->_post('username');
    $Session_idCard_name = $this->_getSession('temp', '__TEMP')['idCard'] . $this->_getSession('temp', '__TEMP')['name'];
    if ($post_name_idCard == $Session_idCard_name) {
      $data['status'] = API_SUCCESS;
      $data['is_realname'] = 1;
      showJsonResult($data);
    }
    showJsonMsg(API_FAILURE, '姓名或身份证号不正确');
  }

  // 第三步-忘记密码-设置登录密码
  public function do_forget_three() {
    $temp = $this->_getSession('temp', '__TEMP');
    $mobilePhone = $temp['mobilephone'];
    $randomCode = $temp['code'];
    $loginPwd = _DecryptPost('password');

    $this->load->model('v2/Password_model', 'Password_model');

    $ret = $this->Password_model->initLoginPwd($mobilePhone, $loginPwd, $randomCode);
    if (API_HDB_SUCCESS == $ret['status']) {
      showJsonMsg(API_SUCCESS, $ret['result']['resmsg'], '/login/index');
    }
    showJsonMsg(API_FAILURE, $ret['info']);
  }
}