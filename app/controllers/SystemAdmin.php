<?php
/**
 * 首页与商户中心
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class SystemAdmin extends qb_Controller
{

    /**
     * 操作员管理
     */
    public function operator()
    {
        $loginNameFind = $this->_get('loginNameFind');
        $phone = $this->_get('phone');
        $flag = $this->_get('flag');
        $this->load->model('SystemAdmin_model', 'systemAdminModel');
        $list = $this->systemAdminModel->getOperatorList($loginNameFind, $phone, $flag);

        $this->smarty->assign('list', $list);
        $this->smarty->assign('flag', $this->systemAdminModel->flag);
        $this->smarty->view('systemAdmin/operator');
    }

    /**
     * 修改密码或修改初始密码
     */
    public function modifyPassword()
    {
        //获取密码控件相关参数
        $passKey = $this->UserAccount_model->getPassSecurityKey();

        $userInfo = $this->_getSession('userInfo', 'global');
        $assign = [
            'passKey' => $passKey,
            'userFlag' => $userInfo['userFlag'],
            'httpHost'=>$this->_server('HTTP_HOST'),
        ];

        $this->smarty->assign($assign);

        $this->smarty->view('systemAdmin/modifyPassword');
    }

    /**
     *
     * 修改密码或修改初始密码执行
     * /systemAdmin/modifyPasswordDo3
     *
     */
    public function modifyPasswordDo()
    {
        $newPassword = $this->_post('newPassword');
        $oldPassword = $this->_post('oldPassword');

        $this->load->model('SystemAdmin_model', 'systemAdminModel');
        $rst = $this->systemAdminModel->modifyPasswordDo($newPassword, $oldPassword);
        showJsonMsg(API_SUCCESS, '修改成功');
    }

    /**
     *  /systemAdmin/addAdmin
     * 新增操作员页面
     */
    public function addAdmin()
    {
        //获取密码控件相关参数
        $passKey = $this->UserAccount_model->getPassSecurityKey();
        $assign = [
            'passKey' => $passKey,
            'httpHost'=>$this->_server('HTTP_HOST'),
        ];
        $this->smarty->assign($assign);
        $this->smarty->view('systemAdmin/addAdmin');
    }

    /**
     *   /systemAdmin/addAdminDo
     * 新增操作员动作
     */
    public function addAdminDo()
    {
        $loginName = $this->_post('loginName');
        $realname = $this->_post('realname');
        $phone = $this->_post('phone');
        $email = $this->_post('email');
        $password = $this->_post('password');

        $this->load->model('SystemAdmin_model', 'systemAdminModel');
        $info = $this->systemAdminModel->addOperator($loginName, $realname, $phone, $email, $password);
        showJsonMsg(API_SUCCESS, '新增成功');
    }

    /**
     * 修改操作员页面
     */
    public function amendAdmin()
    {
        $opLoginName = $this->_get('opLoginName');
        $this->load->model('SystemAdmin_model', 'systemAdminModel');
        $info = $this->systemAdminModel->getOperatorInfo($opLoginName);
        //获取密码控件相关参数
        $passKey = $this->UserAccount_model->getPassSecurityKey();

        $assign = [
            'info' => $info,
            'passKey' => $passKey,
            'httpHost'=>$this->_server('HTTP_HOST'),
        ];
        $this->smarty->assign($assign);
        $this->smarty->view('systemAdmin/amendAdmin');
    }

    /**
     * 修改操作员操作
     * /systemAdmin/amendAdminDo
     */
    public function amendAdminDo()
    {
        $opLoginName = $this->_post('opLoginName');
        $phone = $this->_post('phone');
        $email = $this->_post('email');
        $password = $this->_post('password');

        $this->load->model('SystemAdmin_model', 'systemAdminModel');
        $info = $this->systemAdminModel->modifyOperatorInfo($opLoginName, $phone, $email, $password);
        showJsonMsg(API_SUCCESS, '修改成功');
    }

    /**
     * 停用/启用 操作员
     * 停用  /systemAdmin/disableOrEnable?action=disable
     * 启用  /systemAdmin/disableOrEnable
     */
    public function disableOrEnable()
    {
        $opLoginName = $this->_post('opLoginName');
        $action = $this->_post('action') === 'disable' ? 'disable' : 'enable';

        $this->load->model('SystemAdmin_model', 'systemAdminModel');
        $action = $action . 'Operator';
        $info = $this->systemAdminModel->$action($opLoginName);
        showJsonMsg(API_SUCCESS, '修改成功');
    }

}
