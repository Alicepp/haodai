<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/12/16
 * Time: 14:18
 */
class Check_Getparameter {

  /**
   * 获取get请求某个URL参数是否存在
   */
  public function begin($params = array()) {
    $ci = getCiInstance();
    if (!empty($ci->_get('source_channel'))) {
      $ci->_setCookie('source_channel', $ci->_get('source_channel'));
    }
  }

}
