<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends qb_Controller {

    public function index() {
        $ret = $this->Qbao_model->getListActivity('03');

        $token = !empty($this->_server('http_hdb_token'))?$this->_server('http_hdb_token') : $this->_getCookie('hdbtoken');
        $ret['token'] = !empty($token) ? '&hdb_token=' . $token : '';
        API_QB_SUCCESS != $ret['status'] && !isset($ret['result']) && $ret['result'] = array();
        $ret['title'] = '活动列表';

        $this->smarty->view('activity/activity_index', $ret);
    }

}
