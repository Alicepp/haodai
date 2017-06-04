<?php

function common_redirect($redirect_url, $request = true) {
  if (is_app()) {//app
    $redirect_url .= '?source=app';
  } else {//H5
    $redirect_url .= '?source=h5';
  }

  goRedirect(API_SUCCESS, $redirect_url, '', $request);
}

/*
 * 判断请求来源 app|h5 (兼容老版本)
 * return bool
 * */

function is_app() {
  $CI = get_instance();
  $app = array('ios', 'android', 'app');
  if (in_array(strtolower($CI->_server('http_source')), $app) || in_array(strtolower($CI->_get('hdbfrom')), $app) || in_array(strtolower($CI->_getCookie('source')), $app)) {//app
    return true;
  } else {//h5
    return false;
  }
}

/*
 * 兼容老版本--根据app版本对h5和原生交互进行判断
 * is_new_interaction:true(前端和原生app进行交互) 反之
 * */

function is_new_interaction() {
  $CI = get_instance();
  $version = !empty($CI->_server('http_version')) ? str_replace('.', '', $CI->_server('http_version')) : false;
  $version >= 300 ? $interaction = true : $interaction = false;
  return $interaction;
}

/*
 * 缓存 登录用户信息
 * */
function _get_cache_userinfo($key = '') {
  $CI = get_instance();

  $ret = $CI->My_model->userAccountData();
  if (API_HDB_SUCCESS == $ret['status']) {
    $userInfo = $ret['result'];
  } else {
    return false;
  }

  if (!empty($key) && array_key_exists($key, $userInfo)) {
    return $userInfo[$key];
  } else {
    return $userInfo;
  }
}

/*
 * 是否显示下载app的提示
 *
 * bool  true   显示下载提示
 * bool  false  隐藏下载提示
 * @return bool
 * */
function isShowDownloadApp() {
  $CI = get_instance();
  $isDownload = $CI->_getCookie('isDownload');

  return $isDownload === null && !is_app();
}

/*
 * 设置隐藏下载app的提示
 * */
function setHideDownloadCookieKey() {
  $CI = get_instance();

  $CI->_setCookie('isDownload', 1, 0);

}

/*
 * 获取header头
 * */
function setHeader() {
  $CI = get_instance();
  $source = $CI->_server('http_source');
  empty($source) && $source == $CI->_getCookie('source');

  if (empty($CI->_getCookie('source')) && !empty($CI->_server('http_source'))) {
    $CI->_setCookie('source', $source);
  }

  //权限  _server>_get>_getCookie
  $token = !empty($CI->_server('http_hdb_token')) ? $CI->_server('http_hdb_token') : $CI->_get('hdb_token');
  !empty($CI->_getCookie('hdbtoken')) ? $token : !empty($token) && $CI->_setCookie('hdbtoken', $token);
  $header['source'] = $source;
  $header['hdb_token'] = $token;

  return $header;
  }


  /*
   * 请求需要发送的header
   * */
function send_header() {
  $CI = get_instance();
  $channel = empty($CI->_getCookie('source_channel')) && $CI->_server('channel');
  empty($channel) && $channel = 'h5_m';
  $Header = [
    'source: h5',
    'platform: hdb',
    'deviceToken: xxxxxx',
    'User-Agent: ' . $CI->_server('HTTP_USER_AGENT'),
    'Content-Type: application/json; charset=utf-8',
    'channel: ' . $channel,
    'version: ' . STATIC_DATE,
    'Uni-Source: hdb/Server (PHP)',
//    'Uni-Source: hdb/h5',
  ];

  return $Header;
}

/*
 * 判断用户是否登录
 * return bool false:未登录 true:已登录
 * */
function is_login() {

  static $islogin = null;
  if (!isset($islogin)) {
    $CI = get_instance();
    $ret = $CI->LoginRegister_model->loginCheck();
    if (!$ret || 'false' == $ret['result']['login']) {//未登录
      is_app() && goRedirect(API_SUCCESS,'/error/tokentimeout',API_LOGIN_FAILURE_MSG);
      $islogin = false;//未登录
    }else{
      $islogin = true;
    }
  }

  return $islogin;
}

/*-----------------身份证号验证---------------------------start*/
/*
 * 身份证号验证
 * */
function validation_filter_id_card($id_card) {
  if (strlen($id_card) == 18) {
    return idcard_checksum18($id_card);
  } elseif ((strlen($id_card) == 15)) {
    $id_card = idcard_15to18($id_card);
    return idcard_checksum18($id_card);
  } else {
    return false;
  }
}

// 计算身份证校验码，根据国家标准GB 11643-1999
function idcard_verify_number($idcard_base) {
  if (strlen($idcard_base) != 17) {
    return false;
  }
  //加权因子
  $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
  //校验码对应值
  $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
  $checksum = 0;
  for ($i = 0; $i < strlen($idcard_base); $i++) {
    $checksum += substr($idcard_base, $i, 1) * $factor[$i];
  }
  $mod = $checksum % 11;
  $verify_number = $verify_number_list[$mod];
  return $verify_number;
}

// 将15位身份证升级到18位
function idcard_15to18($idcard) {
  if (strlen($idcard) != 15) {
    return false;
  } else {
    // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
    if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false) {
      $idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
    } else {
      $idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
    }
  }
  $idcard = $idcard . idcard_verify_number($idcard);
  return $idcard;
}

// 18位身份证校验码有效性检查
function idcard_checksum18($idcard) {
  if (strlen($idcard) != 18) {
    return false;
  }
  $idcard_base = substr($idcard, 0, 17);
  if (idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))) {
    return false;
  } else {
    return true;
  }
}

/*-----------------身份证号验证---------------------------end*/


function _DecryptPost($input) {
  $CI = get_instance();
//  //lxm修改
//  LoadClass('helperRsa') || showError();
//  return helperRsa::Decrypt($CI->_post($input), JSPHP_PWD_PRIVKEY);
  return $CI->_post($input);
}


//对象转数组,使用get_object_vars返回对象属性组成的数组
function objectToArray($obj){
  $arr = is_object($obj) ? get_object_vars($obj) : $obj;
  if(is_array($arr)){
    return array_map(__FUNCTION__, $arr);
  }else{
    return $arr;
  }
}

//处理时间戳
function formatStrtotime($input, $format = 'Y-m-d') { //strtotime('-7 day')
  $input = !is_numeric($input)?strtotime($input):$input;
  return date($format, substr($input,0,10));
}