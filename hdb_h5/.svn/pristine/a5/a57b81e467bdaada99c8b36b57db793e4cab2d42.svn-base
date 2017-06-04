<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/11/28
 * Time: 16:59
 *
 */
class Hdbpc_model extends Base_model {
  protected function _init() {
    $this->_host = HDB_PC_URL;
  }

  /*
   * 理财计划
   * */
  public function getPlanInfoDetail($did = 0) {
    $ret = $this->fetchGet('appAccount/queryPlanInfoDetail?planId=' . $did);
    if (API_QB_SUCCESS != $ret['status'] || empty($ret['result'])) {
      showError();
    }

    if (1 == $ret['result']['periodLimitUnit']) {
      $ret['result']['periodLimitType'] = '天';
    } elseif (2 == $ret['result']['periodLimitUnit']) {
      $ret['result']['periodLimitType'] = '个月';
    } else {
      $ret['result']['periodLimitType'] = '年';
    }

    /*
     * 不合理PHP不理解,但后台要求直接写死
     * */
    $ret['result']['interestRule'] = 1;
    $ret['result']['interestRuleType'] = '按日返息,到期还本';

    return $ret;
  }
}