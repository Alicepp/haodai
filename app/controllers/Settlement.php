<?php
/**
 * 首页与商户中心
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Settlement extends qb_Controller
{

    /**
     * /settlement/SettlementManage
     */
    public function settlementManage()
    {
        $data['startDate'] = $this->_get('startDate');
        $data['endDate'] = $this->_get('endDate');
        $data['currency'] = $this->_get('currency');
        $page = $this->_get('page');
        //$number = $this->_get('number'); //页码

        $this->load->model('Settlement_model', 'SettlementModel');
        $rst = $this->SettlementModel->getSettlementList($page, 10, $data);

        //检索参数
        $this->smarty->assign('data', $data);

        $this->smarty->assign('return', $rst);

        $this->smarty->view('settlement/settlementManage');
    }

    //对账单下载页面
    public function bill()
    {

        $this->load->model('Settlement_model', 'SettlementModel');
        //获取账单类型权限枚举
        $billEnum = $this->SettlementModel->getBillEnum();
        $this->smarty->assign('billEnum', $billEnum);
        $this->smarty->view('settlement/bill');
    }

    //对账单下载动作
    public function billDownload()
    {
        $billDate = $this->_post('billDate');
        $productType = $this->_post('productType');

        $this->load->model('Settlement_model', 'SettlementModel');
        //获取账单下载的地址
        $downloadUrl = $this->SettlementModel->getBillDownUrl($billDate, $productType);
        showJsonMsg(API_SUCCESS, '成功',$downloadUrl);
    }

}
