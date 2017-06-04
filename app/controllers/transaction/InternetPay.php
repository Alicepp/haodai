<?php
/**
 * 交易管理相关
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class InternetPay extends qb_Controller
{

    /**
     * 信用卡收单列表
     *  /transaction/InternetPay/internetPayList
     */
    public function internetPayList()
    {
        $data['id'] = $this->_get('id');//平台流水号
        $data['outTradeNo'] = $this->_get('outTradeNo');//业务订单号
        $data['status'] = $this->_get('status');//交易状态
        $data['startCreateTimeStr'] = $this->_get('startCreateTimeStr');
        $data['endCreateTimeStr'] = $this->_get('endCreateTimeStr');
        $page = $this->_get('page');
        //$number = $this->_get('number'); //页码

        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getInternetPayList($data, $page);

        //是否有申请退款“按钮”权限{*目前只有互联网支付有退款--2017-05-09*}
        $urlPath = '/payment/v1/moms/trade/refund/doRefund';
        $permission = $this->Base_model->checkPathPermission($urlPath);
        $permission = isset($permission['result']) && $permission['result'] === '1' ? true : false;

        //业务变更
        $bizUpdate = $this->TransactionModel->bizUpdateInternetPay;
        //状态
        $transStatus = $this->TransactionModel->transStatus;
        //支付方式
        $payType = $this->TransactionModel->payType;

        $assign = ['bizUpdate' => $bizUpdate,//业务变更
                   'payType' => $payType,//支付方式
                   'transStatus' => $transStatus,//交易状态
                   'data' => $data,//检索参数
                   'lastYearNow' => strtotime('-1 year'),//去年今日
                   'return' => $rst,//返回列表
                   'permission' => $permission,//申请退款“按钮”权限
        ];
        $this->smarty->assign($assign);
        $this->smarty->view('transaction/internetPay/internetPayList');
    }

    /**
     * 信用卡详情
     * /transaction/internetPay/detail
     */
    public function detail()
    {
        //平台流水号
        $id = $this->_get('id');

        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getInternetPayDetail($id);

        //业务变更
        $bizUpdate = $this->TransactionModel->bizUpdateInternetPay;
        //状态
        $transStatus = $this->TransactionModel->transStatus;
        //支付方式
        $payType = $this->TransactionModel->payType;

        $assign = ['bizUpdate' => $bizUpdate,//业务变更
                   'payType' => $payType,//支付方式
                   'transStatus' => $transStatus,//交易状态
                   'data' => $rst
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('transaction/internetPay/detail');
    }

}
