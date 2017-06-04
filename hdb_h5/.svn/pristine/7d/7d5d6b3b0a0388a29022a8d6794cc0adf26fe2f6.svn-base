<?php

function codeimg($width = 70, $height = 25, $len = 4, $border = FALSE) {
  $code = ''; //create_captcha
  for ($i = 0; $i < $len; $i++) {
    $code .= dechex(mt_rand(0, 15));
  }

  $img = imagecreatetruecolor($width, $height);
  imagefill($img, 0, 0, imagecolorallocate($img, 255, 255, 255));

  for ($i = 0; $i < 6; $i++) { //干扰线
    $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imageline($img, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $color);
  }

  for ($i = 0; $i < 100; $i++) { //背景
    $color = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
    imagestring($img, 1, mt_rand(1, $width), mt_rand(1, $height), '*', $color);
  }

  for ($i = 0; $i < strlen($code); $i++) { //验证码
    $color = imagecolorallocate($img, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200));
    imagestring($img, 5, $i * $width / $len + mt_rand(1, 5), mt_rand(1, $height / 2), $code[$i], $color);
  }

  if ($border) { //黑色边框
    imagerectangle($img, 0, 0, $width - 1, $height - 1, imagecolorallocate($img, 0, 0, 0));
  }

  ob_start();
  imagepng($img);
  $result = ob_get_contents();
  ob_end_clean();

  imagedestroy($img);

  return ['status' => API_SUCCESS, 'message' => '成功', 'time' => time(), 'image' => base64_encode($result), 'code' => $code];
}


/*
 * 获取图形验证码并赋值到smarty模板
 * */
function getCodeImg($source = '') {
  $CI = get_instance();
  $image = '';
  if ('login' == $source) {
    $CI->load->model('v2/Other_model', 'Other_model');
    $uuid = $CI->_getSession('uuid', '_CODEIMG');// 获取登录密码错误次数超高3次时后台接口返回的uuid
    $ret = $CI->Other_model->getValidateCodeImage($uuid);
    if (API_HDB_SUCCESS == $ret['status']) {
      $image = 'data:image/jpg;base64,' . $ret['result']['image'];
      $CI->_setSession('uuid', $ret['result']['uuid'], '_CODEIMG');
    }
  } else {
    $codeImg = codeimg();
    $CI->_setSession('code', $codeImg['code'], '_CODEIMG');
    $image = 'data:image/jpg;base64,' . $codeImg['image'];
  }

  $data['image'] = $image;
  isAjax() && showJsonResult($data, '');
  $CI->smarty->assign('code', $image);
}

/*
 * 验证图形验证码
 * */
function verifyVerifyCode($imageCode = '') {
  $CI = get_instance();
  empty($imageCode) && $imageCode = $CI->_post('imageCode');
  empty($imageCode) && showJsonMsg(API_FAILURE, '验证码不能为空');
  $imageCode != $CI->_getSession('code', '_CODEIMG') && showJsonMsg(API_FAILURE, '图形验证码输入有误');
}