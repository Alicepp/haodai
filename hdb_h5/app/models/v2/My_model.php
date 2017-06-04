<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/19
 * Time: 18:04
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_model extends Base_model {


  protected function _init() {
    parent::_init();
    $this->_host = HDB_URL_v2;
  }

  protected function fetchFinish($data) {
    $data = parent::fetchFinish($data);
    $keys = ['globalStatus', 'tradeDate', 'approvedPeriod', 'interestdate', 'createdate', 'publishDate'];
    return update_array_value($data, $keys);
  }

    /*
     * 投资记录
     * @param string  mid       用户Id
     * @param Integer bidType   标类型: 0.日息宝 1.精选项目 2.银行宝 3.全部 4.新手标
     * @param Integer status    状态（暂不考虑）
     * @param Integer pageNo   第几页 默认第一页
     * @param Integer pageSize  每页显示数 默认15条
     * */
    public function bidInvestRecords($pageIndex, $pageSize, $status, $bidType) {
        $param['bidType'] = isUInt($bidType, 0, 4) ? $bidType : 3;
        $param['status'] = $status;
        $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
        $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;

        $ret = $this->fetchPost('auth/bidInvestRecords', $param);
        if (0 == $bidType && API_HDB_SUCCESS == $ret['status']) {
            if (array_key_exists('memberInvestList', $ret['result'])) {
                foreach ($ret['result']['memberInvestList'] as $key => &$value) {
                    $value['borrowTitle'] = '活期日息宝';
                }
            }
        }
        if($bidType == '0'){
          foreach ($ret['result']['memberInvestList'] as $key => &$value) {
            $value['amount']/=100;
          }
        }

        $key = ['amount'];
        update_array_value($ret, $key);
        return $ret;
    }

    /*
     * 投资记录
     * @param Integer bidType   标类型: 0.日息宝 1.精选项目 2.银行宝 3.全部 4.新手标 5.定期日息宝
     * @param Integer status    状态（暂不考虑）
     * @param String rxbShow    标类型为全部时，活期日息宝是否显示（0：没有显示，1：已显示）
     * @param Integer pageNo    第几页 默认第一页
     * @param Integer pageSize  每页显示数 默认15条
     * */
    public function bidInvestRecordsDivide($pageIndex, $pageSize, $status, $bidType,$rxbShow=0) {//改接口接口文档不完善 type字段没有说明
        $param['bidType'] = is_numeric($bidType) ? $bidType : 3;
        $param['status'] = $status;
        $param['rxbShow'] = is_numeric($rxbShow) ? $rxbShow : 0;
        $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
        $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;
        $ret = $this->fetchPost('auth/bidInvestRecordsDivide', $param);

        if (API_HDB_SUCCESS == $ret['status']) {
          foreach ($ret['result']['memberInvestList'] as $key => &$value) {
            if(array_key_exists('accountRemin', $value)){//数据统一
              $value['borrowTitle'] = '活期日息宝';
              $value['amount'] = $value['accountRemin']/100;//活期余额（单位分） （活期）
              $value['createdate'] = $value['lastCreateDate'];//最后转入时间（活期）
              $value['yearInterest'] = sprintfNum($value['sevenDayRate']*365);//逾期年化
              $value['bidRecheckDate'] = $value['interestdate'];
            }elseif(0 == $bidType){
              $value['bidRecheckDate'] = $value['interestdate'];
              $value['borrowTitle'] = '活期日息宝';
              $value['amount'] /=100;
            }
          }
        }

        return $ret;
    }

  /*
   * 收支明细
   * @param Integer queryType 类型 0.全部 1.收入 2.支出
   * @param Integer pageNo   第几页 默认第一页
   * @param Integer pageSize  每页显示数 默认15条
   * */
  public function balanceOfPayments($pageIndex, $pageSize, $queryType) {
    $param['queryType'] = isUInt($queryType, 0, 2) ? $queryType : 0;
    $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
    $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;
    $ret = $this->fetchPost('auth/balanceOfPayments', $param);
    return $ret;
  }

  /*
   * 查询用户回款记录
   * @param string  mid       用户Id
   * @param Integer type      还款状态 1 已收 2 待收 0 全部
   * @param Integer pageNo    页码
   * @param Integer pageSize  页条数
   * */
  public function getMemberRefundList($pageIndex, $pageSize, $type) {
    $param['type'] = isUInt($type, 0, 2) ? $type : 0;
    $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
    $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;

    $ret = $this->fetchPost('auth/member/getMemberRefundList', $param);
    return $ret;
  }

  /*
   * 消息置为已读
   * @param Integer msgId 消息Id
   * */
  public function readSysMsg($msgId) {
    $param['msgId'] = $msgId;

    $ret = $this->fetchPost('auth/readSysMsg', $param);
    return $ret;
  }

  /*
   * 查询用户实名&银行卡信息
   * 特殊编号3
   * http://192.168.1.149/index.php?s=/18&page_id=191
   * */

  /*
   * 忘记交易密码 身份验证
   * 特殊编号3
   * http://192.168.1.149/index.php?s=/18&page_id=198
   * */

  /*
   * 查询是否首次投资
   * */
  public function checkMemberIsNew() {
    if (is_login()) {
      $ret = $this->fetchPost('auth/checkMemberIsNew');
    } else {
      $ret = false;
    }
    return $ret;
  }

  /*
   * 计算提现手续费
   * @param string withdrawAmount 提现金额
   * */
  public function getWithdrawFee($withdrawAmount) {
    $param['withdrawAmount'] = $withdrawAmount;

    $ret = $this->fetchPost('p/member/getWithdrawFee', $param);
    return $ret;
  }

  /*
   * 获取用户绑定银行卡列表
   * */
  public function getBindCardList() {

    $ret = $this->fetchPost('auth/member/getBindCardList');
    if (API_HDB_SUCCESS == $ret['status'] && 1 == $ret['result'][0]['bindStatus']) {//已绑卡
      array_key_exists('dayLimit', $ret['result'][0]) && $ret['result'][0]['dayLimit'] == 0 && $ret['result'][0]['dayLimit'] = '无限额';
      array_key_exists('onceLimit', $ret['result'][0]) && $ret['result'][0]['onceLimit'] == 0 && $ret['result'][0]['onceLimit'] = '无限额';
    }

    return $ret;
  }

  /*
   * 提现
   * @param string withdrawAmount 提现金额
   * @param string tradePwd       交易密码
   * @param string cardId 	      卡id
   * */
  public function withdraw($withdrawAmount, $tradePwd, $cardId) {
    empty($withdrawAmount) && showJsonMsg(API_FAILURE, '请输入提现金额');
    $withdrawAmount < 2 && showJsonMsg(API_FAILURE, '提现金额必须大于等于2元,最高限额30万元');
    strpos($withdrawAmount, '0') === 0 && showJsonMsg(API_FAILURE, '不能输入0开头的数字');
    !is_numeric($withdrawAmount) && showJsonMsg(API_FAILURE, '请输入正确的提现金额');
    if ($amountStrstr = strstr($withdrawAmount, '.')) {
      strlen($amountStrstr) > 3 && showJsonMsg(API_FAILURE, '请输入2位小数点以内的金额');
    }

    $param['withdrawAmount'] = $withdrawAmount;
    $param['tradePwd'] = $tradePwd;
    $param['cardId'] = $cardId;

    $ret = $this->fetchPost('auth/member/withdraw', $param);
    return $ret;
  }

  /*
   * 充值记录
   * @param string pageNo   页码
   * @param string pageSize 页条数
   * */
  public function rechargeRecord($pageIndex, $pageSize) {
    $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
    $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;

    $ret = $this->fetchPost('auth/member/rechargeRecord', $param);
    return $ret;
  }

  /*
   * 获取提现记录
   * @param string pageNo   页码
   * @param string pageSize 页条数
   * */
  public function withdrawRecord($pageIndex, $pageSize) {
    $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
    $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;


    $ret = $this->fetchPost('auth/member/withdrawRecord', $param);
    return $ret;
  }

  /*
   * 获取银行列表以及限额
   * 特殊编号2
   * */
  public function getSupportBankList() {
    $ret = $this->fetchPost('p/member/getSupportBankList');
    return $ret;
  }

  /*
   * 个人消息中心
   * @param string appType  app类型（1: ios，0：android）
   * */
  public function getMySysMsg($pageIndex = 1, $pageSize = 15) {
    $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
    $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;

    $ret = $this->fetchPost('auth/getMySysMsg', $param);
    return $ret;
  }

  /*
   * url http://192.168.1.149/index.php?s=/18&page_id=280
   * title 我的账户信息
   * @param string appType  app类型（1: ios，0：android）
   * */
  public function userAccountData() {
    $ret = $this->fetchPost('auth/userAccountData');
    $key = ['accountRemin'];//把活期日息宝金额单位由分转换为元
    update_array_value($ret['result'], $key);
    return $ret;
  }

  /*
   * url    http://192.168.1.149/index.php?s=/18&page_id=290
   * title  快捷签约
   * 参数名	              必选	类型	  说明
   * $param authId        N string 实名id(已实名认证的必需)
   * $param credentialsNo N string 身份证号(未实名认证的必需)
   *
   * $param accountName   Y string 开户姓名
   * $param mobilePhone   Y string 银行预留手机号
   * $param bankCardNo    Y string 银行卡号
   * $param bankCode      Y string 银行代码
   * $param bankName      Y string 开户行完整名称
   * $param amount        Y string 充值金额
   * */
  public function quickPaySign(array $param) {
    $ret = $this->Realname_model->queryRealname();
    if (API_HDB_SUCCESS == $ret['status'] && 1 == $ret['result']['status']) {
      //已实名
      $param['authId'] = $ret['result']['id'];
      unset($param['credentialsNo']);
      $realName = $ret['result']['realName'];
    } else {//未实名
      unset($param['authId']);
      $param['credentialsNo'] = $this->_post('idCard');
      $realName = $this->_post('realName');
    }
    $param['accountName'] = $realName;
    $ret = $this->fetchPost('auth/member/quickPaySign', $param);
    return $ret;
  }


  /*
   * url    http://192.168.1.248:8090/api/auth/member/quickPay
   * developer  邓英兵
   * title  快捷支付
   * 参数名	              必选	类型	  说明
   * $param authId        N string 实名id(已实名认证的必需)
   * $param credentialsNo N string 身份证号(未实名认证的必需)
   *
   * $param cardId        N string 代扣卡id（非首次代扣必需）
   *
   * $param mobilePhone   N string 银行预留手机号(首次代扣必需)
   * $param bankCardNo    N string 银行卡号(首次代扣必需)
   * $param sign          N string 签约信息id(首次代扣必需)
   * $param verifyCode    N string 验证码(首次代扣必需)
   *
   * $param accountName   Y string 开户姓名
   * $param bankCode      Y string 银行代码
   * $param bankName      Y string 开户行完整名称
   * $param amount        Y string 充值金额
   * */
  public function quickPay(array $param) {
    empty($param['amount']) && showJsonMsg(API_FAILURE, '请输入充值金额');
    !is_numeric($param['amount']) && showJsonMsg(API_FAILURE, '充值金额只能为数字');
    if ($amountStrstr = strstr($param['amount'], '.')) {
      strlen($amountStrstr) > 3 && showJsonMsg(API_FAILURE, '请输入2位小数点以内的金额');
    }
    strpos($param['amount'], '0') === 0 && showJsonMsg(API_FAILURE, '不能输入0开头的数字');

    if (isEnv('development|testing')) {
      $param['amount'] < 1 && showJsonMsg(API_FAILURE, '充值金额必须大于等于1元');
    } else {
      $param['amount'] < 100 && showJsonMsg(API_FAILURE, '充值金额必须大于等于100元');
    }

    $ret = $this->Realname_model->queryRealname();
    if (API_HDB_SUCCESS == $ret['status'] && 1 == $ret['result']['status']) {
      //已实名
      $param['authId'] = $ret['result']['id'];
      unset($param['credentialsNo']);
      $realName = $ret['result']['realName'];
    } else {//未实名
      unset($param['authId']);
      $param['credentialsNo'] = $this->_post('idCard');
      $realName = $this->_post('realName');
      empty($realName) && showJsonMsg(API_FAILURE, '请输入中文姓名');
      empty($param['credentialsNo']) && showJsonMsg(API_FAILURE, '请输入正确的身份证号码');
    }
    $param['accountName'] = $realName;

//    $User_bankCard = $this->getBindCardList();//获取用户绑定的银行卡

//    if (4527 == $User_bankCard['status'] || 0 == $User_bankCard['result'][0]['bindStatus']) {
//      //首次代扣
//      unset($param['cardId']);
//    } else {
//      //非首次代扣
//      unset($param['mobilePhone'], $param['bankCardNo'], $param['sign'], $param['verifyCode']);
//    }
    $ret = $this->fetchPost('auth/member/quickPay', $param);

    return $ret;
  }

}