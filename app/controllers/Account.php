<?php
/**
 * 账户管理相关
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends qb_Controller
{

    /**
     *  /account/AccountManage
     */
    public function accountManage()
    {
        //获取账户信息
        $accountInfo = $this->UserAccount_model->getAccountInfo();
        //echo '++47++';var_dump($accountInfo);die;

        $this->smarty->assign('accountInfo', $accountInfo);

        $data['accountType'] = $this->_get('accountType') ? $this->_get('accountType') : '01';//账户类型
        $data['orderId'] = $this->_get('orderId');//交易订单号
        $data['tradeType'] = $this->_get('tradeType');//交易类型
        $data['startDate'] = $this->_get('startDate');
        $data['endDate'] = $this->_get('endDate');
        $page = $this->_get('page');//页码
        //$number = $this->_get('number'); //每页条数

        $rst = $this->UserAccount_model->getAccountGeneralJournal($data, $page);

        //账户类型
        $accountType = $this->UserAccount_model->accountType;
        //交易类型
        $tradeType = $this->UserAccount_model->tradeType;

        $assign = [
            'accountType' => $accountType,
            'tradeType' => $tradeType,
            'data' => $data,//检索参数
            'return' => $rst,
        ];
        $this->smarty->assign($assign);

        $this->smarty->view('account/accountManage');
    }

}
