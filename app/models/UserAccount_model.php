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

class UserAccount_model extends Base_model
{

    //账户类型
    public $accountType = ['01' => '基本账户', '02' => '待清算户', '03' => '营销账户', '04' => '信用卡还款户'];

    //交易类型
    public $tradeType = ['3' => '转账', '5' => '冻结', '6' => '解冻', '9' => '调账', '11' => '入账', '12' => '出账', '13' => '解冻出账', '14' => '解冻转账',];

    protected function _init()
    {
        parent::_init();
    }


    /**
     * //根据token，账户信息
     *  杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/account/info
     * @return array
     */
    public function getAccountInfo()
    {
        //获取账户信息
        $rst = $this->fetchPost('/payment/v1/moms/account/info');
        $tmp = [];
        foreach ($rst['result'] as $v) {
            $tmp[$v['accountType']] = $v;
        }
        return $tmp;
    }

    /**
     * //根据token，当前用户信息，并存储在session中
     *  杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/util/getLoginUser
     * @return array
     */
    public function getUserInfo($readSession = true, $sessionType = 'session')
    {
        $userInfo = [];
        if ($readSession) {
            if (strtolower($sessionType) === self::SESSION_SESSION) {
                $userInfo = $this->_getSession('userInfo', 'global');
            }
        }
        if ($userInfo) {
            return $userInfo;
        }
        $rst = $this->fetchPost('/payment/v1/moms/util/getLoginUser');
        $userInfo = $rst['result'];
        if (strtolower($sessionType) === self::SESSION_SESSION) {
            $this->_setSession('userInfo', $userInfo, 'global');
        }
        return $userInfo;
    }

    /**
     * //根据token，获取密码控件相关参数
     *  杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/util/getPassSecurityKey
     * @return array
     */
    public function getPassSecurityKey()
    {
        $rst = $this->fetchPost('/payment/v1/moms/util/getPassSecurityKey');
        return $rst['result'];
    }

    /**
     * //根据token，获取加密因子
     *  杨东辉 yangdh@qianbaoeco.com
     * 访问地址 开发 : https://dev-apis.qianbao.com/payment/v1/moms/util/getMcryptKey
     * @return array
     */
    public function getMcryptKey($token = '')
    {
        $token = $token ? $token : self::$token;
        $param = ['token' => $token];
        return $this->fetchPost('/payment/v1/moms/util/getMcryptKey', $param);
    }

    /**
     * 根据token，获取图片验证码
     *  杨东辉 yangdh@qianbaoeco.com
     * 访问地址 开发 : https://dev-apis.qianbao.com/payment/v1/moms/verifyCode
     * @return array
     */
    public function getVerifyCode()
    {
        $token = self::$token;
        $url=$this->_host.'/payment/v1/moms/verifyCode?token='.$token;
        //echo $url;
        $img=file_get_contents($url);
        return $img;
        /*
        $url='/payment/v1/moms/verifyCode?token='.$token;
        $this->_initCurl($url = $this->_parseUrl($url));
        $result = $this->_curl->postJson($url);
        var_dump($result);
        die;
        $rst=$this->fetchPost('/payment/v1/moms/verifyCode?token='.$token);
        */
    }


    /**
     * //获取手机验证码
     *    *  杨东辉 yangdh@qianbaoeco.com
     * 开发 : https://dev-apis.qianbao.com/payment/v1/moms/util/sendPhoneVerifyCode
     * @param $customerNo string  商户号
     * @param $loginName  string  登录名
     * @param $verifyCode string  验证码
     * @param string $bizCode string
     * @return array
     */
    public function sendPhoneVerifyCode($customerNo, $loginName, $verifyCode, $bizCode = 'LOGIN')
    {
        //表单验证
        //echo '++163++';var_dump(strlen($customerNo),preg_match('/[^\da-z]/i',$loginName));
        if (!$customerNo || !isUInt($customerNo) || strlen($customerNo) > 15) {
            showError('商户号输入格式不正确');
        }
        if (!$loginName || preg_match('/[^\da-z]/i', $loginName)) {
            showError('登录名输入格式不正确');
        }
        if (!$verifyCode || preg_match('/[^\da-z]/i', $verifyCode) || strlen($verifyCode) > 6) {
            showError('验证码输入不正确');
        }

        $param['customerNo'] = $customerNo;
        $param['loginName'] = $loginName;
        $param['verifyCode'] = $verifyCode;
        $param['bizCode'] = $bizCode;

        $rst = $this->fetchPost('/payment/v1/moms/util/sendPhoneVerifyCode', $param);
        return $rst;
    }

    /**
     * 执行登录
     * 开发 : https://dev-apis.qianbao.com/payment/v1/moms/login/doLogin
     *  *  杨东辉 yangdh@qianbaoeco.com
     * @param $customerNo 商户号
     * @param $loginName 登录名
     * @param $password 密码
     * @param $phoneVerifyCode 手机验证码
     * @return array
     */
    public function doLogin($customerNo, $loginName, $password, $phoneVerifyCode)
    {
        //表单验证
        //echo '++163++';var_dump(strlen($customerNo),preg_match('/[^\da-z]/i',$loginName));
        if (!$customerNo || !isUInt($customerNo) || strlen($customerNo) > 15) {
            showError('商户号输入格式不正确');
        }
        if (!$loginName || preg_match('/[^\da-z]/i', $loginName)) {
            showError('登录名输入格式不正确');
        }
        if (!$phoneVerifyCode || !isUInt($phoneVerifyCode) || strlen($phoneVerifyCode) > 6) {
            showError('验证码输入不正确');
        }

        $param['customerNo'] = $customerNo;
        $param['loginName'] = $loginName;
        $param['password'] = $password;
        $param['phoneVerifyCode'] = $phoneVerifyCode;

        $rst = $this->fetchPost('/payment/v1/moms/login/doLogin', $param);
        //更新token（99%可能与最初获得的token一样）
        $this->writeLogin('', $rst['result'], self::SESSION_COOKIE);
        //echo '++180++';var_dump($rst);die;
        return $rst;
    }

    /**
     * 退出登录（没有任何操作token 10分钟过期）
     * 开发 : https://dev-apis.qianbao.com/payment/v1/moms/login/doLogout
     *  *  杨东辉 yangdh@qianbaoeco.com
     * @return array
     */
    public function logout()
    {
        $this->_clearSession();
        $this->clearLogin(self::LOGIN_COOKIE);
        $ret = $this->fetchPost('/payment/v1/moms/login/doLogout');
    }

    /**
     * 获取账户流水信息(分页)
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/account/show
     * @param $data 检索参数
     * @param int $page 页码
     * @param int $number 每页条数
     * @return array
     */
    public function getAccountGeneralJournal($data, $page = 1, $number = 10)
    {
        $param = array_filter($data);
        $param['page'] = isUInt($page, 1) ? $page : 1;
        $param['number'] = isUInt($number, 1) ? $number : 10;

        isset($param['startDate']) && $param['startDate'] = date('Y-m-d', strtotime($param['startDate']));
        isset($param['endDate']) && $param['endDate'] = date('Y-m-d', strtotime($param['endDate']));

        $rst = $this->fetchPost('/payment/v1/moms/account/show', $param);
        return $rst['result'];
    }
    //*************************************************************************************

    /*
     * 登录
     * @param string Y userName 用户名
     * @param string Y passWord 密码
     *
     * 密码错误次数超限是必传
     * @param string N validCode 验证码
     * @param string N uuid 验证码与用户名对应的id
     * @return json
     * */
    public function login($userName, $passWord, $imageCode = '')
    {
        $param['userName'] = $userName;
        $param['passWord'] = $passWord;

        $param['validCode'] = $imageCode;
        !empty($imageCode) && $param['uuid'] = $this->_getSession('uuid', '_CODEIMG');//获取session中是否存在uuid 默认为空

        $ret = $this->fetchPost('p/login', $param);
        if (API_HDB_SUCCESS == $ret['status']) {
            $this->writeLogin($ret['result']['mid'], $ret['result']['token'], self::SESSION_COOKIE, 1800);

            goRedirect(API_SUCCESS, '', '', '/home/index');
        } else {
            $message = $ret['message'];
            if (4629 === $ret['status'] && !empty($ret['result']) && array_key_exists('image', $ret['result'])) {//密码错误次数超过限定的3次
                !empty($param['uuid']) && $message = '用户名密码或验证码错误';
                empty($imageCode) && $message = '';

                $this->_setSession('uuid', $ret['result']['uuid'], '_CODEIMG');
                $ret['result']['image'] = 'data:image/jpg;base64,' . $ret['result']['image'];

                unset($ret['result']['uuid']);

                showJsonResult($ret['result'], $message, API_PWD_FAILURE_OVERRUN);
            }
            showJsonMsg(API_FAILURE, $ret['message']);
        }

    }


}
