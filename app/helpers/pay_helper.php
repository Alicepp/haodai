<?php



//**************************************************
function commonAssign($menu,$ci=null){
  is_object($ci) || $ci = getCiInstance();
  $ci->smarty->assign('menu', $menu);
  $ci->smarty->assign('userInfo', $ci->_getSession('userInfo'));
  $ci->smarty->assign('firstLevelMenu', $ci->_getSession('firstLevelMenu'));
  return true;
}

function common_redirect($redirect_url, $request = true) {
  if (is_app()) {//app
    $redirect_url .= '?source=app';
  } else {//H5
    $redirect_url .= '?source=h5';
  }

  goRedirect(API_SUCCESS, $redirect_url, '', $request);
}

/*
 * 获取header头
 * */
function setHeader() {
  $CI = get_instance();
  $source = get_server_key_value('source');
  !empty($source) && $CI->_getCookie('source') !== $source && $CI->_setCookie('source', $source);

  //权限  _server>_get>_getCookie
  $token =  get_server_key_value('token',$CI->_get('hdb_token'));

  !empty($token) && $CI->_getCookie('hdbtoken') !== $token && $CI->_setCookie('hdbtoken', $token);
}


  /*
   * 请求需要发送的header
   * */
function send_header() {
  $Header = [
    'Content-Type: application/json; charset=utf-8',
    'platform: hdb',
    'Uni-Source: hdb/Server (PHP)',

    'User-Agent: ' . get_server_key_value('user_agent'),
    'source: h5',
    'channel: '.get_server_key_value('channel','31000'),
    'version: '.STATIC_DATE,
    'deviceToken: xxxxxx',
    'imei: xxxxxx',
//    'os: '.get_server_key_value($CI,'http_'.$prefix.'os','xxxxxx'),
//    'phoneType: '.get_server_key_value($CI,'http_'.$prefix.'phonetype','xxxxxx'),
//    'osVersion: '.get_server_key_value($CI,'http_'.$prefix.'osversion','xxxxxx'),
//    'screen: '.get_server_key_value($CI,'http_'.$prefix.'screen','xxxxxx'),
  ];

  return $Header;
}

/*
 * 获取指定key的header头的值-->支持新老自定义header 头参数的获取
 * 如果值为空则返沪$value参数
 * @param key header头参数名
 * @param value header头参数名的值为空时返回的值
 * return string|int|$value
 * */
function get_server_key_value($key,$value='') {
  $CI = get_instance();
  $prefix = rtrim(config_item('variable_prefix'), '-');
  $key = strtolower($key);

  $http_key = 'http_' . $key;
  $http_prefix_key = 'http_' . $prefix . $key;
  if ($result = $CI->_server($http_key)) {//老规范
    return $result;
  } elseif ($result = $CI->_server($http_prefix_key)) {//新规范
    return $result;
  } else {
    return $value;
  }
}


function _DecryptPost($input) {
  $CI = get_instance();
//  //lxm修改
  LoadClass('helperRsa') || showError();
  return helperRsa::Decrypt($CI->_post($input), JSPHP_PWD_PRIVKEY);
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