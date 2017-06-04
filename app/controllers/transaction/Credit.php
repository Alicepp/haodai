<?php
/**
 * 交易管理相关
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Credit extends qb_Controller
{

    /**
     * 信用卡收单列表
     *  /transaction/credit/creditCardPay
     */
    public function creditCardPay()
    {
        $data['id'] = $this->_get('id');//平台流水号
        $data['outTradeNo'] = $this->_get('outTradeNo');//业务订单号
        $data['terminalNo'] = $this->_get('terminalNo');//终端号
        $data['status'] = $this->_get('status');  //交易状态
        $data['processStatus'] = $this->_get('processStatus');  //处理状态
        $data['startDate'] = $this->_get('startDate');
        $data['endDate'] = $this->_get('endDate');

        $page = $this->_get('page');
        //$number = $this->_get('number'); //页码

        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getCreditPayList($data, $page);

        //处理状态
        $processStatusCredit = $this->TransactionModel->processStatus;
        //币种设置
        $currency = $this->TransactionModel->currency;
        //状态
        $transStatus = $this->TransactionModel->transStatus;

        $assign = ['processStatusCredit' => $processStatusCredit,    //处理状态
                   'transStatus' => $transStatus,    //交易状态
                   'currency' => $currency,    //币种设置
                   'data' => $data,//检索参数
                   'return' => $rst,//数据列表
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('transaction/credit/creditCardPay');
    }

    /**
     * 信用卡详情
     * /transaction/credit/detail
     */
    public function detail()
    {
        //平台流水号
        $id = $this->_get('id');

        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getCreditCardPayDetail($id);

        //处理状态
        $processStatusCredit = $this->TransactionModel->processStatus;

        //状态
        $transStatus = $this->TransactionModel->transStatus;

        $assign = ['processStatusCredit' => $processStatusCredit,    //处理状态
                   'transStatus' => $transStatus,    //交易状态
                   'data' => $rst,//数据列表
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('transaction/credit/detail');
    }

}
