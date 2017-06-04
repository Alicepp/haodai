<?php
/**
 * 交易管理相关
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Recharge extends qb_Controller
{

    /**
     * 充值列表
     *
     */
    public function accountRecharge()
    {
        $data['id'] = $this->_get('id');//平台流水号
        $data['outTradeNo'] = $this->_get('outTradeNo');//业务订单号
        $data['status'] = $this->_get('status');
        $data['startCreateTimeStr'] = $this->_get('startCreateTimeStr');
        $data['endCreateTimeStr'] = $this->_get('endCreateTimeStr');
        $page = $this->_get('page');
        //$number = $this->_get('number'); //页码

        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getRechargeList($data, $page);

        //状态
        $transStatus = $this->TransactionModel->transStatus;

        $assign = [
            'transStatus' => $transStatus,    //交易状态
            'data' => $data, //检索参数
            'return' => $rst, 'currency' => $this->TransactionModel->currency,
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('transaction/recharge/accountRecharge');
    }

    /**
     * 充值详情
     */
    public function detail()
    {
        //平台流水号
        $id = $this->_get('id');
        $this->load->model('Transaction_model', 'TransactionModel');
        $rst = $this->TransactionModel->getRechargeDetail($id);

        //状态
        $transStatus = $this->TransactionModel->transStatus;

        $assign = [
            'transStatus' => $transStatus,    //交易状态
            'data' => $rst, 'currency' => $this->TransactionModel->currency,
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('transaction/recharge/detail');
    }


}
