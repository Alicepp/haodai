<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/14
 * Time: 14:18
 */
/*
 * 检查用户登录状态
 * 设置登录白名单
 * */

//设置登录白名单
class Check_Login {

  public function begin($params = array()) {
    $ci = getCiInstance();
    $class = strtolower($ci->router->directory . $ci->router->class);
    $method = strtolower($ci->router->method);
    $loginArr = [
      'proxy',
      'common',
      'home' => ['index', 'safe', 'token', 'aboutus', 'more','service','about','huodongus'],
      'help', //帮助
      'notice', //通知
      'activity', //活动
      'error', //错误页面
      'login', //登录
      'register', //注册
      'day', //说明
      'my/cashvalue' => ['banklist'], //发卡银行
      'my/rules' => ['index'], //充值提现规则
      'my/info' => ['index'], //我的首页
      'my/coupon' => ['index', 'getcouponlist'], //我的首页
      'financial_plan/agreement' => ['service'], //理财计划协议（静态）
      'bid' => [
        'intro',
        'income',
        'question',
        'project',
        'prolist',
        'bidlog',
        'regulardetail',
        'calculator',
        'getcouponid',
        'earn',
        'agreement_bid_do',
        'staticexplain',
        'risktip',
        'staticintro',
        'financial_planning',
      ],
      'automatic_bid',
    ];

    if (!(in_array($class, $loginArr) || (array_key_exists($class, $loginArr) && in_array($method, $loginArr[$class])))) {//需要登录
      if (!is_login()) {//未登录
        if (is_app()) {
          goRedirect(API_FAILURE, '/error/tokentimeout', '登录超时,请重新登录');
        }
        else {//H5
          goRedirect(API_FAILURE, '/login/index', '登录超时,请重新登录');
        }
      }
//      _get_cache_userinfo();
    }
  }

}
