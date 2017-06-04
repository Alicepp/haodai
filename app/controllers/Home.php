<?php
/**
 * 首页
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends qb_Controller
{

    /**
     * 首页
     *  直接域名访问 或 域名/home/index
     */
    public function index()
    {
        //获取账户信息
        $accountInfo = $this->UserAccount_model->getAccountInfo();
        //echo '++47++';var_dump($accountInfo);die;

        $this->load->model('Settlement_model', 'SettlementModel');
        $settleMentList = $this->SettlementModel->getSettlementList(1, 5);

        $assign=[
            'settleMentList'=> $settleMentList['infoList'],
            'accountInfo'=>$accountInfo,
            'userInfo'=>$this->_getSession('userInfo', 'global'),
            'currency'=>$this->SettlementModel->currency,
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('home');
    }

    /**
     * 登录页面
     * /home/login
     */
    public function login()
    {
        //获取密码控件相关参数
        $passKey = $this->UserAccount_model->getPassSecurityKey();

        $prefix = rtrim(config_item('variable_prefix'), '_-');
        $token = $this->_getCookie($prefix . 'token');

        $assign=[
            'token'=>$token,
            'passKey'=>$passKey,
            'httpHost'=>$this->_server('HTTP_HOST'),
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('login');
    }

    /**
     * 获取加密因子
     */
    public function getMcryptKey()
    {
        $mcryptKey = $this->UserAccount_model->getMcryptKey();
        //var_dump($mcryptKey);die;
        showJsonResult($mcryptKey['result']);
    }

    /**
     *获取图片验证码
     */
    public function verifyCode(){
        $img = $this->UserAccount_model->getVerifyCode();
        //var_dump($img);die;
        header('Content-type: image/jpeg');
        echo $img;
        //showJsonResult($mcryptKey['result']);
    }
    /**
     * 请求手机验证码
     */
    public function sendPhoneVerifyCode()
    {
        $customerNo = $this->_post('customerNo');//	商户号	String
        $loginName = $this->_post('loginName');//		登录名	String
        $verifyCode = $this->_post('verifyCode');//	验证码	String
        $rst = $this->UserAccount_model->sendPhoneVerifyCode($customerNo, $loginName, $verifyCode);

        if (API_SUCCESS === $rst['status']) {
            showJsonMsg(API_SUCCESS, '发送成功');
        } else {
            showJsonMsg(API_FAILURE, $rst['message']);
        }
    }

    //执行登录
    public function doLogin()
    {
        $customerNo = $this->_post('customerNo');//	商户号	String
        $loginName = $this->_post('loginName');//		登录名	String
        $password = $this->_post('password');//	密码	String
        $phoneVerifyCode = $this->_post('phoneVerifyCode');//	手机验证码	String

        $rst = $this->UserAccount_model->doLogin($customerNo, $loginName, $password, $phoneVerifyCode);
        //$rst['status']='20000107';
        //$rst['info']='成功';
        //$rst['message']='成功';
        //$rst['result']='665C8F9BED27498CB81A6678302C63B0';
        if (API_SUCCESS === $rst['status']) {
            //登录成功跳转首页
            showJsonMsg(API_SUCCESS, '登录成功', '/');
        } else {
            showJsonMsg(API_FAILURE, $rst['message']);
        }
    }

    /**
     * 退出登录
     * /home/logout
     */
    public function logout()
    {
        $rst = $this->UserAccount_model->logout();
        redirect('/home/login');
    }

}
