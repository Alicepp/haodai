<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Qbao_model extends qb_Model {

  protected function _init() {
    $this->_host = QB_URL;
  }

  protected function fetchBefore($url, $data) {
    $header = [
      'version: 1.1.0',
      'source: m',
      'deviceToken: xxxxxx',
      'imei: xxxxxx',
      'platform: hdb',
//      'imei:88128381237123',
    ];

    $this->setRequestHeader($header);

    if (strpos($url, '/api/pub/')) {
      $body = jsonencode(['content' => empty($data) ? '' : jsonencode($data)]);
    } else {
      $body = jsonencode(['content' => DES3Encrypt(jsonencode($data), API_KEY)]);
    }
    return $body;

  }

  protected function fetchAfter($url, $data) {
    if (isset($data['result']) && is_string($data['result'])) {
      $data['result'] = jsondecode($data['result']);
    }
    return $data;
  }

  protected function fetchFinish($data) {
    return $data;
  }

  // 获取活动中心数据v3
  public function getListActivity($type) {
    $param = ['imgType' => $type];
    return $this->fetchPost('/api/pub/v3/fp/getListActivity', $param);
  }

  /*
   * 获取banner首页和理财页 v3
   * /api/pub/v3/fp/getListBanner（张劼）
   *
   * @param string Y position       banner类型
   * @param string Y imgType        图片类型
   * @param string n fpProductType  对应钱包理财的标的类型
   * */
  public function getListBanner() {
    $param['position'] = 1;
    $param['imgType'] = '02';
    $ret = $this->fetchPost('/api/pub/v3/fp/getListBanner', $param);
    if ($ret && API_QB_SUCCESS == $ret['status'] && !empty($ret['result'])) {
      $ret = array_slice($ret['result'], 0, 3);
    } else {
      $ret = array();
    }
    return $ret;
  }

  // 获取系统公告列表
  public function getSystemMessageList($pageIndex = 1, $pageSize = 15) {
    $param['pageNo'] = isUInt($pageIndex) ? $pageIndex : 1;
    $param['pageSize'] = isUInt($pageSize) ? $pageSize : 15;
    $ret = $this->fetchPost('/api/pub/v3/fp/getSystemMessageList', $param);

    if ($ret && API_QB_SUCCESS == $ret['status']) {
      $ret = $ret['result'];
    } else {
      $ret = array();
    }

    return $ret;
  }

  // 获取一条系统公告 内容
  public function getOneSystemMessage($messageId = '') {
    $param['messageId'] = $messageId;
    $ret = $this->fetchPost('/api/pub/v3/fp/getOneSystemMessage', $param);
    if (!$ret || API_QB_SUCCESS != $ret['status']) {
      $ret = array();
    }
    return $ret;
  }

  /*
   * title 获取首页4个入口信息v3 张孝伟
   * url   http://192.168.1.149/index.php?s=/5&page_id=98
   * @param imgType String 图片类型{01小图 02中图 03大图}
   * */
  public function getListHome($imgType = '02') {
    $param['imgType'] = $imgType;
    $ret = $this->fetchPost('/api/pub/v3/fp/getListHome', $param);
    if ($ret && API_QB_SUCCESS == $ret['status'] && !empty($ret['result'])) {
      $ret = array_slice($ret['result'], 0, 3);
    } else {
      $ret = array();
    }
    return $ret;
  }

  /*
   * title 意见建议v3 张劼
   * url   http://192.168.1.149/index.php?s=/5&page_id=74
   * @param memberId    String 会员编号
   * @param mobilePhone String 手机号
   * @param content     String 建议内容
   * */
  public function submitSuggestion($mobilePhone, $content) {
    $param['mobilePhone'] = $mobilePhone;
    try {
      checkForm('account/mobile', $param);
    } catch (Exception $e) {
      showJsonMsg(API_FAILURE, $e->getMessage());
    }
    $param['content'] = $content;
    $param['memberId'] = $this->SIGN_UID;

    $ret = $this->fetchPost('/api/p/v3/fp/submitSuggestion', $param);
    if (!$ret || API_QB_SUCCESS != $ret['status']) {
      $ret = array();
    }
    return $ret;
  }

}
