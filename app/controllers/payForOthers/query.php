<?php
/**
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class query extends qb_Controller
{

    /**
     *  /payForOthers/query/batchQuery
     */
    public function batchQuery()
    {

        $this->smarty->view('payForOthers/query/batchQuery');
    }


}
