<?php
/**
 * 商户信息、用户信息、权限信息
 * 密码管理
 * 系统管理
 * 商户中心
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SystemAdmin_model extends Base_model
{

    //操作员状态
    public $flag = ['1' => '启用', '0' => '停用', 'I' => '初始化'];

    //商户中心--企业性质
    public $customerProperty=['E'=>'企业','P'=>'个人'];
    //商户中心--开户账户类型
    public $oaType=['BC'=>'银行对公','BU'=>'银行对私','CC'=>'企业会员','CU'=>'个人会员'];
    //商户中心--银行卡类型
    public $cardType =['DC'=>'借记卡','CC'=>'贷记卡','SC'=>'准贷记卡',];

    protected function _init()
    {
        parent::_init();
    }

    /**
     * 获取操作员列表数据
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/user/system/showOpUser
     * @param $loginNameFind 登录名
     * @param $phone 电话
     * @param $flag    状态,启用 : 1    停用 : 0    初始化 : I
     * @return array
     */
    public function getOperatorList($loginNameFind, $phone, $flag)
    {
        $param = [];
        $loginNameFind && $param['loginNameFind'] = $loginNameFind;
        isUInt($phone) && $param['phone'] = $phone;
        $flag !== '' && $param['flag'] = $flag;

        $rst = $this->fetchPost('/payment/v1/moms/user/system/showOpUser', $param);
        return $rst['result'];
    }

    /**
     * 获取单个操作员信息
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/user/system/getOpUserInfo
     * @param $opLoginName 登录名
     * @return array
     */
    public function getOperatorInfo($opLoginName)
    {
        if (!$opLoginName) {
            return [];
        }

        $rst = $this->fetchPost('/payment/v1/moms/user/system/getOpUserInfo', ['opLoginName' => $opLoginName]);
        return $rst['result'];
    }

    /**
     * 获取商户信息、清结算信息
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址开发 : https://dev-apis.qianbao.com/payment/v1/moms/merchant/info/show
     * @return array
     */
    public function getMerchantInfo()
    {
        $rst = $this->fetchPost('/payment/v1/moms/merchant/info/show');
        return $rst['result'];
    }

    /**
     * 修改操作员
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址开发 : https://dev-apis.qianbao.com/payment/v1/moms/user/system/doModifyOpUser
     * @param $opLoginName 登录名
     * @param $phone 手机号
     * @param $email 邮箱
     * @param string $password 用密码控件加密的密文, 不修改传空
     * @return array
     */
    public function modifyOperatorInfo($opLoginName, $phone, $email, $password = '')
    {
        $param['opLoginName'] = $opLoginName;
        $param['phone'] = $phone;
        $param['email'] = $email;
        $param['password'] = $password;
        //var_dump($param);
        $rst = $this->fetchPost('/payment/v1/moms/user/system/doModifyOpUser', $param);
        return $rst;
    }

    /**
     * 执行修改密码接口
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址 开发 : https://dev-apis.qianbao.com/payment/v1/moms/user/password/doChange
     * @param $newPassword 新密码
     * @param $oldPassword 原始密码
     * @return array
     */
    public function modifyPasswordDo($newPassword, $oldPassword)
    {
        $param = ['newPassword' => $newPassword, 'oldPassword' => $oldPassword];
        $rst = $this->fetchPost('/payment/v1/moms/user/password/doChange', $param);
        return $rst['result'];
    }

    /**
     * 启用操作员
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址 开发 : https://dev-apis.qianbao.com/payment/v1/moms/user/system/doOpenOpUser
     * @param $opLoginName 登录名
     * @return array
     */
    public function enableOperator($opLoginName)
    {
        $param['opLoginName'] = $opLoginName;
        //echo '++105++';var_dump($param);
        $rst = $this->fetchPost('/payment/v1/moms/user/system/doOpenOpUser', $param);
        return $rst;
    }

    /**
     * 禁用操作员 停用操作员
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/user/system/doCloseOpUser
     * @param $opLoginName 登录名
     * @return array
     */
    public function disableOperator($opLoginName)
    {
        $param['opLoginName'] = $opLoginName;
        //echo '++105++';var_dump($param);
        $rst = $this->fetchPost('/payment/v1/moms/user/system/doCloseOpUser', $param);
        return $rst;
    }

    /**
     * 创建操作员
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址 开发 : https://dev-apis.qianbao.com/payment/v1/moms/user/system/doCreateOpUser
     * @param $loginName 登录名
     * @param $realname 姓名
     * @param $phone 手机号
     * @param $email 邮箱
     * @param string $password 用密码控件加密的密文, 不修改传空
     * @return array
     */
    public function addOperator($loginName, $realname, $phone, $email, $password)
    {
        if (!preg_match('/^[0-9a-zA-Z]{4,20}$/', $loginName) || $realname === '' || !preg_match('/^(13|14|15|17|18)\d{9}$/', $phone) || !preg_match('/^(\w)+(\.\w+)*@(\w)+(\.\w+)+$/', $email) || !$password) {
            showError('参数格式错误');
        }

        $param['loginName'] = $loginName;
        $param['realname'] = $realname;
        $param['password'] = $password;
        $param['phone'] = $phone;
        $param['email'] = $email;
        //var_dump($param);
        $rst = $this->fetchPost('/payment/v1/moms/user/system/doCreateOpUser', $param);
        return $rst;
    }

    /**
     * 商户证书上传
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址 开发 : https://dev-apis.qianbao.com/payment/v1/moms/merchant/key/upload
     * @param $file
     * @return array
     */
    public function uploadCertificate($file, $maxSize = 500000)
    {

        //1.判断文件上传是否错误
        if ($file['error'] > 0 || !$file['tmp_name']) {
            showJsonMsg(API_FAILURE, '上传错误，请稍后再试');
        }
        //2.判断上传文件类型是否合法
        if ($file['type'] !== 'application/x-x509-ca-cert') {
            showJsonMsg(API_FAILURE, '请上传cer格式的文件');
        }
        //3.判断上传文件大小是否超出允许值，此处暂定500KB
        if ($file['size'] > $maxSize) {
            showJsonMsg(API_FAILURE, '文件过大');
        }
        //4.判断是否是上传的文件，并移动文件
        if (is_uploaded_file($file['tmp_name'])) {
            $param['file'] = '@' . $file['tmp_name'];
            $rst = $this->fetchPost('/payment/v1/moms/merchant/key/upload', $param);
            return $rst;
        } else {
            showJsonMsg(API_FAILURE, '上传错误，请稍后再试');
        }
    }

}
