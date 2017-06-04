<?php
/**
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class detailCheck extends qb_Controller
{

    /**
     * 代付出款，明细查询
     *  /payForOthers/detailCheck/detail
     */
    public function detail()
    {

        $this->smarty->view('payForOthers/detailCheck/detail');
    }

}
