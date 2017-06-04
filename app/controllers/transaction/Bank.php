<?php
/**
 * 交易管理相关
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends qb_Controller
{

    /**
     * 银行卡收单列表
     */
    public function bankCard()
    {
        $data['id'] = $this->_get('id');//平台流水号
        $data['outTradeNo'] = $this->_get('outTradeNo');//业务订单号
        $data['terminalNo'] = $this->_get('terminalNo');//终端号
        $data['tradeType'] = $this->_get('tradeType');
        $data['transStatus'] = $this->_get('transStatus');
        $data['startDate'] = $this->_get('startDate');
        $data['endDate'] = $this->_get('endDate');
        $data['srcId'] = $this->_get('srcId');//原交易流水号
        $page = $this->_get('page');
        //$number = $this->_get('number'); //页码

        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getBankcardPayList($data, $page);

        //业务变更、交易类型
        $bizUpdate = $tradeType = $this->TransactionModel->tradeTypeBank;
        //状态
        $transStatus = $this->TransactionModel->transStatus;

        $assign = ['bizUpdate' => $bizUpdate,//业务变更
                   'tradeType' => $tradeType,//交易类型
                   'transStatus' => $transStatus,//交易状态
                   'data' => $data,//检索参数
                   'lastYearNow' => strtotime('-1 year'),//去年今日
                   'return' => $rst,//返回列表
        ];
        $this->smarty->assign($assign);
        $this->smarty->view('transaction/bank/bankCard');
    }

    /**
     * 银行卡收单详情
     */
    public function detail()
    {
        //平台流水号
        $id = $this->_get('id');

        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getBankcardPayDetail($id);

        //业务变更、交易类型
        $bizUpdate = $tradeType = $this->TransactionModel->tradeTypeBank;
        //状态
        $transStatus = $this->TransactionModel->transStatus;

        $assign = ['bizUpdate' => $bizUpdate,    //业务变更
                   'tradeType' => $tradeType,    //交易类型
                   'transStatus' => $transStatus,    //交易状态
                   'data' => $rst,];
        $this->smarty->assign($assign);

        $this->smarty->view('transaction/bank/detail');
    }

    /**
     * 退款
     */
    public function refund()
    {
        $data['id'] = $this->_get('id');//平台流水号
        $data['amount'] = intval($this->_post('amount'));
        $data['refundAmount'] = intval($this->_post('refundAmount'));

        $this->smarty->assign('data', $data);
        $this->smarty->view('transaction/refund');
    }

    /**
     * 申请退款操作
     */
    public function refundDo()
    {
        $id = $this->_post('id');//平台流水号
        $refundAmount = $this->_post('refundAmount');

        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->refundDo($id, $refundAmount);
        showJsonMsg(API_SUCCESS, '操作成功');
    }


}
