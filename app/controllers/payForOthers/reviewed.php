<?php
/**
 * 交易管理相关
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class reviewed extends qb_Controller
{

    /**
     * 银行卡收单详情
     */
    public function payReviewed()
    {

        $this->smarty->view('payForOthers/reviewed/payReviewed');
    }

    /**
     * 退款
     */
    public function reviewedDetail()
    {

        $this->smarty->view('payForOthers/reviewed/reviewedDetail');
    }


}
