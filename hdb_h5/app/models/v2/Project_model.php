<?php

/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/19
 * Time: 15:59
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends Base_model {

  protected function _init() {
    parent::_init();
    $this->_host = HDB_URL_v2;
  }

  protected function fetchFinish($data) {
    $data = parent::fetchFinish($data);
    $keys = ['globalStatus', 'approvedPeriod'];
    return update_array_value($data, $keys);
  }

  /*
   * 获取项目列表
   * @param string pageNo     页码
   * @param string pageSize   一页条数
   * */
  public function getBidList($pageIndex, $pageSize) {
    $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
    $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;

    $ret = $this->fetchPost('p/bid/getBidList', $param);
    return $ret;
  }

  /*
   * 获取同类型的更多项目
   * @param string pageNo       页码
   * @param string pageSize     一页条数
   * @param string projectType  项目类型 1：新手标 2：定期 3：银行宝 4：精选项目
   * */
  public function getMoreBidList($pageIndex, $pageSize, $projectType = 1) {
    $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
    $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;
    $param['projectType'] = isUInt($projectType, 1, 4) ? $projectType : 1;

    $ret = $this->fetchPost('p/bid/getMoreBidList', $param);
    return $ret;
  }

  /*
   * 获取标详情
   * @param string bid  标ID
   * */
  public function bidDetail($bid) {
    $param['bid'] = $bid;
    if (isUInt($bid, 1)) {
      $ret = $this->fetchPost('p/bid/bidDetail', $param);
      return $ret;
    } else {
      return false;
    }
  }

  /*
   * 获取项目投标记录
   * @param string pageNo       页码
   * @param string pageSize     一页条数
   * @param string bid  标ID
   * */
  public function investRecord($pageIndex, $pageSize, $bid) {
    $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
    $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;
    $param['bid'] = $bid;
    if (isUInt($bid, 1)) {
      $ret = $this->fetchPost('p/bid/investRecord', $param);
      return $ret;
    } else {
      return false;
    }
  }

  /*
   * 查询活期日息宝账户数据
   * */
  public function queryPerCurrentBalance() {
    $ret = $this->fetchPost('auth/bid/queryPerCurrentBalance');
    return $ret;
  }

  /*
   * 查询活期日息宝账户数据
   * 特殊编号2
   * */
  public function currentRxbDetail() {
    $ret = $this->fetchPost('p/bid/currentRxbDetail');
    return $ret;
  }

  /*
   * 转入日息宝资金
   * @param string intoAmount 转入金额
   * */
  public function intoCurrentRxb($intoAmount) {
    empty($intoAmount) && showJsonMsg(API_FAILURE, '请输入转入金额');
    !is_numeric($intoAmount) && showJsonMsg(API_FAILURE, '转入金额只能为数字');
    !is_integer((int)$intoAmount) && showJsonMsg(API_FAILURE, '请输入整数金额');

    $param['intoAmount'] = $intoAmount;

    $ret = $this->fetchPost('auth/intoCurrentRxb', $param);
    return $ret;
  }

  /*
   * 转出日息宝资金
   * @param string intoAmount       转入金额
   * @param string tradePwd         交易密码
   * */
  public function currentToBalance($intoAmount, $tradePwd) {
    empty($intoAmount) && showJsonMsg(API_FAILURE, '请输入转出金额');
    !is_numeric($intoAmount) && showJsonMsg(API_FAILURE, '转出金额只能为数字');
    !is_integer((int)$intoAmount) && showJsonMsg(API_FAILURE, '请输入整数金额');
    empty($tradePwd) && showJsonMsg(API_FAILURE, '请输入交易密码');

    $param['outAmount'] = $intoAmount;
    $param['tradePwd'] = $tradePwd;

    $ret = $this->fetchPost('auth/currentToBalance', $param);

    return $ret;
  }

  /*
   * 获取标更多详情
   * */
  public function moreBidDetail($bid) {
    $param['bid'] = $bid;
    if (isUInt($bid, 1)) {
      $ret = $this->fetchPost('p/bid/moreBidDetail', $param);
      return $ret;
    } else {
      return false;
    }
  }

  /*
   * 投标时获取可用优惠券及最优优惠券
   * @param string investAmount 投资金额
   * */
  public function queryUserCoupon($investAmount) {
    $param['investAmount'] = isUInt($investAmount) ? $investAmount : 1;

    $ret = $this->fetchPost('auth/queryUserCoupon', $param);
    $keys = ['resourceAmount', 'limitAmount'];
    return update_array_value($ret, $keys);
  }

  /*
   * 投标（使用优惠券）
   * @param string bid          标 bid
   * @param string investAmount 投资金额
   * @param string resourceCode 优惠券编码（编码可以为空）
   * */
  public function bidWithCoupon($bid, $investAmount, $resourceCode) {
    $param['bid'] = $bid;
    $param['investAmount'] = isUInt($investAmount) ? $investAmount : 0;
    $param['resourceCode'] = !empty($resourceCode) ? $resourceCode : '';
    if (isUInt($bid, 1)) {
      $ret = $this->fetchPost('auth/bidWithCoupon', $param);
      return $ret;
    } else {
      showError();
    }
  }

  /*
   * 理财计划
   * */
  public function getPlanInfoDetail($did = 0) {
    $param['planId'] = $did;
    $ret = $this->fetchPost('p/plan/getPlanInfoDetail', $param);

    if (API_HDB_SUCCESS != $ret['status'] || empty($ret['result']) || 'null' == $ret['result']) {
      showError();
    }
    $ret['result'] = jsondecode($ret['result']);
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

  // 首页查询理财计划类型
  public function getPlanTypeList() {
    $url ='/p/plan/getPlanTypeList';
    $ret = $this->fetchPost($url);
    return $ret;
  }

}