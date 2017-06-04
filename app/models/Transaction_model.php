<?php
/**
 * 交易查询
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction_model extends Base_model
{

    //银行卡收单--交易类型、业务变更
    public $tradeTypeBank = ['REFUND' => '退款', 'SALE' => '消费', 'CANCEL' => '撤销', 'REVERSE' => '冲正',];

    //互联网交易--交易类型
    public $tradeTypeInternetPay = ['PAY' => '支付'];

    //互联网支付--业务变更
    public $bizUpdateInternetPay = ['ALL_REFUND' => '部分退款', 'PART_REFUND' => '全部退款',];

    //交易状态（各种交易均有）
    public $transStatus = ['O' => '创建', 'I' => '受理', 'S' => '成功', 'F' => '失败', 'C' => '关闭'];

    //（互联网支付）交易类型\支付方式
    public $payType = ['ALIPAY' => '支付宝', 'WEIXIN' => '微信', 'BANK' => '银行',];

    //信用卡还款交易处理状态
    public $processStatus = ['DI' => '还款处理中', 'PI' => '扣款处理中', 'PF' => '扣款失败', 'DS' => '信用卡还款成功', 'DF' => '还款失败',];

    //币种设置
    public $currency = ['CNY' => '人民币'];

    //银行卡类型-代付交易
    public $cardType =['DC'=>'借记卡','CC'=>'贷记卡','SC'=>'准贷记卡',];

    //账户属性
    public $bankAccountType=['BC'=>'银行对公','BU'=>'银行对私','CC'=>'企业会员','CU'=>'个人会员',];

    //‘交易查询’菜单id，用于查找二级菜单列表
    public $secondLevelMenuId = 'A69E2ABEA80E4ECBB9B81B90AC7C8074';
    public $secondLevelMenu;

    protected function _init()
    {
        parent::_init();
        $this->secondLevelMenu || $this->secondLevelMenu = $this->getMenuInfo($this->secondLevelMenuId);
    }

    /**
     * 获取银行卡收单交易数据
     *      *  杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/pos/show
     * @param $data 检索参数
     * @param int $page 每页条数
     * @param int $number 页码
     * @return array|false
     */
    public function getBankcardPayList($data, $page = 1, $number = 10)
    {
        $param = array_filter($data);
        $param['page'] = isUInt($page, 1) ? $page : 1;
        $param['number'] = isUInt($number, 1) ? $number : 10;
        isset($param['startCreateTimeStr']) && $param['startCreateTimeStr'] = date('Y-m-d', strtotime($param['startCreateTimeStr']));
        isset($param['endCreateTimeStr']) && $param['endCreateTimeStr'] = date('Y-m-d', strtotime($param['endCreateTimeStr']));

        $rst = $this->fetchPost('/payment/v1/moms/pos/show', $param);
        return $rst['result'];
    }

    /**
     * 获取互联网交易明细数据
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/pos/detail
     * @param $id   平台流水号
     * @return array
     */
    public function getBankcardPayDetail($id)
    {
        if (!$id || !isUInt($id)) {
            return [];
        }
        $param['id'] = $id;
        $rst = $this->fetchPost('/payment/v1/moms/pos/detail', $param);
        return $rst['result'];
    }

    /**
     * 获取互联网交易信息(分页)
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/trade/info/show
     * @param $data 检索参数
     * @param int $page 页码
     * @param int $number 每页条数
     * @return array
     */
    public function getInternetPayList($data, $page = 1, $number = 10)
    {
        $param = array_filter($data);
        $param['page'] = isUInt($page, 1) ? $page : 1;
        $param['number'] = isUInt($number, 1) ? $number : 10;

        isset($param['startCreateTimeStr']) && $param['startCreateTimeStr'] = date('Y-m-d', strtotime($param['startCreateTimeStr']));
        isset($param['endCreateTimeStr']) && $param['endCreateTimeStr'] = date('Y-m-d', strtotime($param['endCreateTimeStr']));

        $rst = $this->fetchPost('/payment/v1/moms/trade/info/show', $param);
        return $rst['result'];
    }

    /**
     * 获取互联网交易明细数据
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/trade/info/detail
     * @param $id   平台流水号
     * @return array
     */
    public function getInternetPayDetail($id)
    {
        if (!$id || !isUInt($id)) {
            return [];
        }
        $param['id'] = $id;
        $rst = $this->fetchPost('/payment/v1/moms/trade/info/detail', $param);
        return $rst['result'];
    }


    /**
     * 获取信用卡还款交易信息(分页)
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/credit/show
     * @param $data 检索参数
     * @param int $page 页码
     * @param int $number 每页条数
     * @return array
     */
    public function getCreditPayList($data, $page = 1, $number = 10)
    {
        $param = array_filter($data);
        $param['page'] = isUInt($page, 1) ? $page : 1;
        $param['number'] = isUInt($number, 1) ? $number : 10;

        isset($param['startDate']) && $param['startDate'] = date('Y-m-d', strtotime($param['startDate']));
        isset($param['endDate']) && $param['endDate'] = date('Y-m-d', strtotime($param['endDate']));

        $rst = $this->fetchPost('/payment/v1/moms/credit/show', $param);
        return $rst['result'];
    }


    /**
     * 获取信用卡还款交易明细数据
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/credit/detail
     * @param $id   平台流水号
     * @return array
     */
    public function getCreditCardPayDetail($id)
    {
        if (!$id || !isUInt($id)) {
            return [];
        }
        $param['id'] = $id;
        $rst = $this->fetchPost('/payment/v1/moms/credit/detail', $param);
        return $rst['result'];
    }

    /**
     * 获取代付交易信息(分页)
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/trade/defray/show
     * @param $data 检索参数
     * @param int $page 页码
     * @param int $number 每页条数
     * @return array
     */
    public function getPayOtherList($data, $page = 1, $number = 10)
    {
        $param = array_filter($data);
        $param['page'] = isUInt($page, 1) ? $page : 1;
        $param['number'] = isUInt($number, 1) ? $number : 10;

        isset($param['startCreateTimeStr']) && $param['startCreateTimeStr'] = date('Y-m-d', strtotime($param['startCreateTimeStr']));
        isset($param['endCreateTimeStr']) && $param['endCreateTimeStr'] = date('Y-m-d', strtotime($param['endCreateTimeStr']));

        $rst = $this->fetchPost('/payment/v1/moms/trade/defray/show', $param);
        return $rst['result'];
    }

    /**
     * 获取代付交易明细数据
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/trade/defray/detail
     * @param $id   平台流水号
     * @return array
     */
    public function getPayOtherDetail($id)
    {
        if (!$id || !isUInt($id)) {
            return [];
        }
        $param['id'] = $id;
        $rst = $this->fetchPost('/payment/v1/moms/trade/defray/detail', $param);
        return $rst['result'];
    }


    /**
     * 获取账户充值交易信息(分页)
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/trade/recharge/show
     * @param $data 检索参数
     * @param int $page 页码
     * @param int $number 每页条数
     * @return array
     */
    public function getRechargeList($data, $page = 1, $number = 10)
    {
        $param = array_filter($data);
        $param['page'] = isUInt($page, 1) ? $page : 1;
        $param['number'] = isUInt($number, 1) ? $number : 10;

        isset($param['startCreateTimeStr']) && $param['startCreateTimeStr'] = date('Y-m-d', strtotime($param['startCreateTimeStr']));
        isset($param['endCreateTimeStr']) && $param['endCreateTimeStr'] = date('Y-m-d', strtotime($param['endCreateTimeStr']));

        $rst = $this->fetchPost('/payment/v1/moms/trade/recharge/show', $param);
        return $rst['result'];
    }

    /**
     * 获取账户充值交易明细数据
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/trade/recharge/detail
     * @param $id   平台流水号
     * @return array
     */
    public function getRechargeDetail($id)
    {
        if (!$id || !isUInt($id)) {
            return [];
        }
        $param['id'] = $id;
        $rst = $this->fetchPost('/payment/v1/moms/trade/recharge/detail', $param);
        return $rst['result'];
    }

    /**
     * 申请退款接口
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址 开发 : https://dev-apis.qianbao.com/payment/v1/moms/trade/refund/doRefund
     * @param $id   平台流水号
     * @param $refundAmount   退款金额
     * @return array
     */
    public function refundDo($id, $refundAmount)
    {
        if (!$id || !isUInt($id) || !$refundAmount) {
            showError('参数错误');
        }
        $param['id'] = $id;
        $param['refundAmount'] = strval(round($refundAmount,2));
        $rst = $this->fetchPost('/payment/v1/moms/trade/refund/doRefund', $param);
        return $rst;
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
