<?php
/**
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class upload extends qb_Controller
{

    /**
     * 代付出款上传
     * /payForOthers/upload/uploadHome
     */
    public function uploadHome()
    {
        //获取账户信息
        $accountInfo = $this->UserAccount_model->getAccountInfo();

        $image=$this->createCaptcha();

        $assign = ['accountInfo' => $accountInfo,'image'=>$image];

        $this->smarty->assign($assign);
        $this->smarty->view('payForOthers/upload/uploadHome');
    }

    /**
     * 生成验证码图片并将验证码存储在session
     * 返回base64格式的图片编码；或ajax下直接返回json，程序退出
     * /payForOthers/upload/createCaptcha
     */
    public function createCaptcha($scope=null)
    {
        $img=codeimg();
        $this->_setSession('captcha',$img['code'],$scope);
        $image = 'data:image/jpg;base64,' . $img['image'];
        $data['image'] = $image;
        isAjax() && showJsonResult($data, '');
        return $image;
    }

    /**
     * 代付出款上传操作
     * /payForOthers/upload/uploadDo
     */
    public function uploadDo()
    {
        $totalAmount=$this->_post('totalAmount');
        $totalCount=$this->_post('totalCount');
        $verifyCode=$this->_post('verifyCode');

        $code=$this->_getSession('captcha');
        if($code!==$verifyCode){
            showError('验证码错误');
        }

        $this->load->model('PayForOthers_model', 'payForOthersModel');
        $rst = $this->payForOthersModel->uploadPayForOthers($totalAmount,$totalCount,$_FILES['file']);

        showJsonMsg($rst);
    }

    /**
     * 代付出款上传失败明细
     * 超过20笔
     *  /payForOthers/upload/FailDescription
     */
    public function failDescription()
    {

        $this->smarty->view('payForOthers/upload/FailDescription');
    }

    /**
     * 代付出款上传失败明细
     * 不到20笔，可编辑
     *  /payForOthers/upload/uploadFailInfo
     */
    public function uploadFailInfo()
    {

        $this->smarty->view('payForOthers/upload/uploadFailInfo');
    }
}
