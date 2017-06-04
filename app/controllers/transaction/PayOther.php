<?php
/**
 * 交易管理相关
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class PayOther extends qb_Controller
{

    /**
     * 代付列表
     *
     */
    public function payForOther()
    {
        $data['id'] = $this->_get('id');//平台流水号
        $data['outTradeNo'] = $this->_get('outTradeNo');//业务订单号
        $data['status'] = $this->_get('status');
        $data['startCreateTimeStr'] = $this->_get('startCreateTimeStr');
        $data['endCreateTimeStr'] = $this->_get('endCreateTimeStr');
        $page = $this->_get('page');
        //$number = $this->_get('number'); //页码

        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getPayOtherList($data, $page);

        //状态
        $transStatus = $this->TransactionModel->transStatus;

        $assign = [
            'transStatus' => $transStatus,    //交易状态
            'data' => $data,//检索参数
            'return' => $rst,
            'currency'=>$this->TransactionModel->currency,
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('transaction/payOther/payForOther');
    }


    /**
     * 代付详情
     */
    public function detail()
    {
        //平台流水号
        $id = $this->_get('id');
        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getPayOtherDetail($id);

        $assign = [
            'payType' => $this->TransactionModel->payType,    //支付方式
            'cardType' => $this->TransactionModel->cardType,    //卡类型
            'bankAccountType' => $this->TransactionModel->bankAccountType,    //账户属性
            'transStatus' => $this->TransactionModel->transStatus,    //交易状态
            'data' => $rst,
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('transaction/payOther/detail');
    }


}
