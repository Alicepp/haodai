<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/11/7
 * Time: 16:01
 */
/*
 * 检查用户实名认证状态
 * */

class Check_Realname {

  public function begin($params = array()) {
    $ci = getCiInstance();
    $class = strtolower($ci->router->directory . $ci->router->class);
    $loginArr = [
      'bid' => ['shift_to', 'buy'],
      'my/realname' => ['certification', 'bankcard', 'do_realname'],
      'my/info' => ['index'],
    ];

    switch ($class) {

      case 'my/cashvalue':
      case 'my/withhold':
        _Super_queryRealname();
        break;

      case 'my/realname':
        if (in_array($ci->router->method, $loginArr[$class])) {
          _Super_queryRealname('smarty'); //实名认证
        } else {
          _Super_queryRealname(); //实名认证
        }
        break;
      case 'my/info':
        if (in_array($ci->router->method, $loginArr[$class])) {
          _Super_queryRealname('smarty'); //实名认证
        }
        break;

      case 'bid':
        if (in_array($ci->router->method, $loginArr[$class])) {
          _Super_queryRealname('smarty'); //实名认证
//          _Super_getBindCardList('do_buy'); //是否绑卡
        }
        break;
    }
  }

}
