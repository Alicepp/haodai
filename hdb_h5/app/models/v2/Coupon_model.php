<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/31
 * Time: 17:20
 */
class Coupon_model extends Base_model {
  protected function _init() {
    $this->_host = RESTHUB_URL;
  }

  /*
   * 请求之前 设置header头
   * */
  protected function fetchBefore($url, $data) {
    return $data;
  }

  /*
   * mobilePhone  用户手机号
   * status       优惠卷状态
   * 0 可用 1 不可用 2 已过期 3 已失效 4 已使用
   * */
  public function Get_my_Coupon($mobilePhone, $status) {
    $data['mobilePhone'] = $mobilePhone;
    $data['status'] = !empty($status) ? $status : 0;
    $ret = $this->fetchGet('haodaibao/appAccount/queryCoupon', $data);
    $keys = ['resourceAmount', 'limitAmount'];
    return update_array_value($ret, $keys);
  }

}