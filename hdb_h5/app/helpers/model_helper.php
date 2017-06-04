<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/11/16
 * Time: 20:12
 * describe: 强化模块
 */


/*---------------My_model--------------------------------------------------*/
/*
 * 获取用户绑定银行卡列表
 * @param super 表示调用来源 判断跳转地址
 * */
function _Super_getBindCardList($super = false) {
  $CI = get_instance();
  $CI->load->model('v2/My_model', 'My_model');
  $ret = $CI->My_model->getBindCardList();

  switch ($super) {
    case 'bankcard'://银行卡
      $ret = $ret;
      break;

    case 'bindcard'://绑卡页面
      if (API_HDB_SUCCESS == $ret['status'] && 1 == $ret['result'][0]['bindStatus'] && 1 == $ret['result'][0]['isDk']) {//已绑卡
        goRedirect(API_SUCCESS, '/my/realname/bankcard', '已绑卡', false);
      }
      break;

    case 'do_buy'://投标
    case 'recharge'://充值
    case 'do_recharge'://充值
      if (API_HDB_SUCCESS == $ret['status'] && 1 != $ret['result'][0]['isDk']) {//未绑定代扣卡
        goRedirect(API_FAILURE, '/my/realname/bindcard', '未绑卡，请先进行绑卡');
      } elseif (API_HDB_SUCCESS != $ret['status']) {
        goRedirect(API_FAILURE, '/my/realname/bankcard');
      }
      break;

    default:
      if (API_HDB_SUCCESS != $ret['status'] && 1 != $ret['result'][0]['bindStatus']) {//未绑卡
        goRedirect(API_FAILURE, '/my/realname/bankcard');
      }
      break;
  }

  $result = API_HDB_SUCCESS == $ret['status'] ? $ret['result'][0] : array();

  return $result;
}


/*
 * 获取银行卡列表
 * @param super 表示调用来源 判断跳转地址
 * */
function _Super_getSupportBankList() {
  $CI = get_instance();
  $CI->load->model('v2/My_model', 'My_model');
  $ret = $CI->My_model->getSupportBankList();

  return $ret['result'];
}

/*
 * 默认是新手
 *return string 0：是新手 1：不是新手
 * */
function _Super_checkMemberIsNew() {
  $CI = get_instance();
  $isNew = 0;
  $ret = $CI->My_model->checkMemberIsNew();
  if (API_HDB_SUCCESS == $ret['status']) {
    $isNew = $ret['result']['isNew'];
  }

  return $isNew;
}

/*---------------Password_model--------------------------------------------------*/
/*
 * 是否设置交易密码
 * $bool true: 返回json
 * $bool false:返回bool
 * */
function _Super_verificationTradePwd($bool = true, $request = true) {
  $CI = get_instance();

  $realname = $CI->Realname_model->queryRealname();
  if (API_HDB_SUCCESS == $realname['status']) {
    if (-1 == $realname['result']['status']) {//实名失败---正在认证中
      goRedirect(API_ALERT_ONE_BUTTON, '', '您的身份信息正在认证中,如有疑问拨打客服电话:400-620-8800',false);
    } elseif (0 == $realname['result']['status']) {//未实名
      goRedirect(API_ALERT_TWO_BUTTON, '/my/realname/certification', '您还没有实名认证，请先实名');
    }
  }

  $ret = $CI->Password_model->verificationTradePwd();
  if (2 == $ret) {//未设置交易密码
    goRedirect(API_FAILURE, '/my/password/forget_deal?title=setpwd', '您还未设置交易密码,请先设置交易密码', $request);
  } else {//已设置交易密码
    if (isAjax() && $bool) {
      showJsonMsg(API_SUCCESS);
    }

    return true;
  }

}

/*---------------Other_model--------------------------------------------------*/
/*
 * 发送验证码
 * */
function send_code($mobilePhone) {
  $CI = get_instance();
  $CI->load->model('v2/Other_model', 'Other');
  $ret = $CI->Other->getPhoneVerificationCode($mobilePhone);

  return $ret;
}


/*---------------LoginRegister_model--------------------------------------------------*/
/*
 * 检测手机号是否注册
 * */
function _Super_phoneExists($username, $super = '') {
  $CI = get_instance();
  $ret = $CI->LoginRegister_model->phoneExists($username);

  switch ($super) {
    case 'do_login'://登录操作
      if (200 == $ret['status']) {//未注册
        goRedirect(API_FAILURE, '/register/index', $ret['message']);
      } elseif (4522 == $ret['status']) {//已注册
        return true;
      } else {
        showJsonMsg(API_FAILURE, $ret['message']);
      }
      break;

    case 'register_get_identifyingCode'://注册--获取验证码
      if (4522 == $ret['status']) {//已注册
        showJsonMsg(API_FAILURE, $ret['message']);
      } elseif (200 == $ret['status']) {
        return true;
      } else {
        showJsonMsg(API_FAILURE, $ret['message']);
      }
      break;

    case 'forgetPwd_get_identifyingCode'://忘记密码--获取验证码
      if (200 == $ret['status']) {//未注册
        showJsonMsg(API_FAILURE, $ret['message'], '/register/index');
      } elseif (4522 == $ret['status']) {
        return true;
      } else {
        showJsonMsg(API_FAILURE, $ret['message']);
      }
      break;
  }

}


/*---------------Realname_model--------------------------------------------------*/
/*
 * 检测实名认证状态
 * */
function _Super_queryRealname($returnDate = 'json') {//特殊编号7
  $CI = get_instance();

  $data['realName'] = '';
  $data['idCard'] = '';
  $data['msg'] = '';
  $data['url'] = '';
  $data['status'] = API_FAILURE;
  $data['time'] = date('Y年m月d日', time());

  if(is_login()){
    static $ret = null;
    empty($ret) && $ret = $CI->Realname_model->queryRealname();

    if (API_HDB_SUCCESS == $ret['status']) {
      $data['status'] = $ret['result']['status'];

      if (1 == $ret['result']['status']) {//实名失败
        $data['idCard'] = $ret['result']['idCard'];
        $data['realName'] = $ret['result']['realName'];
      } elseif (-1 == $ret['result']['status']) {//实名失败
        $data['msg'] = '您的身份信息正在认证中,如有疑问拨打客服电话:400-620-8800';
        $data['url'] = $returnDate === 'json' ? '/my/info' : '';
      } else {//未实名
        $data['msg'] = '未实名认证,请先进行实名认证';
        $data['url'] = '/my/realname/certification';
      }
    }
  }



  if ('smarty' == $returnDate || 1 == $ret['result']['status']) {
    $CI->smarty->assign('Realnameinfo', $data);
  } else {
    goRedirect(API_FAILURE, $data['url'], $data['msg']);
  }
}

/*---------------Project_model--------------------------------------------------*/
/*
 * 活期日息宝账户数据
 * */
function _Super_queryPerCurrentBalance() {
  $CI = get_instance();
  $CI->load->model('v2/Project_model', 'Project_model');
  $ret = $CI->Project_model->queryPerCurrentBalance();
  if (API_HDB_SUCCESS == $ret['status']) {
    $key = ['accountRemin', 'allinterest', 'remainnum','yesterdaygains'];
    update_array_value($ret['result'], $key);

    return $ret['result'];
  }

  return false;
}

/*
 * 查询活期日息宝详情
 * */
function _Super_currentRxbDetail() {
  $CI = get_instance();
  $CI->load->model('v2/Project_model', 'Project_model');
  $ret = $CI->Project_model->currentRxbDetail();
  if (API_HDB_SUCCESS == $ret['status']) {
    $key = ['allamount', 'remainnum'];
    update_array_value($ret['result'], $key);

    return $ret['result'];
  }
  showError();
}

/*
 * 获取可用优惠卷
 * 默认获取可用优惠卷
 * $investAmount == empty 时获取可用优惠卷列表
 * $investAmount != empty 时获最优优惠卷
 * */
function _Super_queryUserCoupon($investAmount = '') {
  !empty($investAmount) && $investAmount <= 100 && showJsonResult([]);

  $CI = get_instance();
  $CI->load->model('v2/Project_model', 'Project_model');
  $ret = $CI->Project_model->queryUserCoupon($investAmount);
  $couponList = [];
  if (API_HDB_SUCCESS == $ret['status']) {
    if (empty($investAmount)) {//获取列表
      $couponList = $ret['result']['couponValidList'];
    } elseif (!empty($ret['result']['couponValidList'])) {//获取最优
      foreach ($ret['result']['couponValidList'] as $key => $value) {
        $value['isBest'] == 1 && $couponList = $value;
      }
    }
  }
  isAjax() && !empty($investAmount) && showJsonResult($couponList);
  isAjax() && empty($investAmount) && showJsonData($couponList);
  $CI->smarty->assign('list', $couponList);

}

/*---------------Coupon_model--------------------------------------------------*/
/*
 *  获取可用优惠卷列表
 * */
function _Super_Get_my_Coupon($status = 0) {
  $CI = get_instance();
  $CI->load->model('v2/Coupon_model', 'Coupon_model');
  $mobilePhone = _get_cache_userinfo('mobilePhone');
  empty($mobilePhone) && showJsonMsg(API_H5_BRIDGE_APP, API_LOGIN_FAILURE_MSG, '/my/cash_coupon');
  $ret = $CI->Coupon_model->Get_my_Coupon($mobilePhone, $status);
  if (isAjax()) {
    if (API_QB_SUCCESS == $ret['status']) {
      empty($ret['result']) && $ret['result'] = [];
      showJsonData($ret['result']);
    } else {
      showJsonMsg(API_H5_BRIDGE_APP, API_LOGIN_FAILURE_MSG, '/my/cash_coupon');
    }
  } else {
    $CI->smarty->assign('list', $ret['result']);
  }
}

/*---------------Other_model--------------------------------------------------*/
//验证登录验证码是否正确
function _Super_checkValidateCode($uuid = '', $code = '') {

  $CI = get_instance();
  $CI->load->model('v2/Other_model', 'Other_model');
  $ret = $CI->Other_model->checkValidateCode($uuid, $code);
  if (API_HDB_SUCCESS == $ret['status']) {
    return true;
  } else {
    showJsonMsg(API_FAILURE, '图形验证码输入有误');
  }
}
