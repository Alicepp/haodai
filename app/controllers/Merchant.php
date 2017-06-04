<?php
/**
 * 首页与商户中心
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant extends qb_Controller
{

    /**
     *商户中心
     */
    public function tenantCenter()
    {
        $this->load->model('SystemAdmin_model', 'systemAdminModel');
        //商户信息、清结算信息
        $merchantInfo = $this->systemAdminModel->getMerchantInfo();

        $assign=[
            'merchantInfo'=>$merchantInfo,
            'customerProperty'=>$this->systemAdminModel->customerProperty,
            'oaType'=>$this->systemAdminModel->oaType,
            'cardType'=>$this->systemAdminModel->cardType,
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('Merchant/tenantCenter');
    }

    /**
     *上传证书
     */
    public function uploadCertificate()
    {
        $this->load->model('SystemAdmin_model', 'systemAdminModel');
        $rst = $this->systemAdminModel->uploadCertificate($_FILES['file']);
        showJsonMsg($rst);
    }

}
