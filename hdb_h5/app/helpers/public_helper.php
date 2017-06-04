<?php

function exitJsonencode($data) {
  if (is_object($data)) {
    foreach ($data as $key => $value) {
      $array[$key] = $value;
    }
  } else {
    $array = $data;
  }
  exit(jsonencode($array));
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $strict = true) {
  $label = ($label === null) ? '' : rtrim($label) . ' ';
  if (!$strict) {
    if (ini_get('html_errors')) {
      $output = print_r($var, true);
      $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
    } else {
      $output = $label . print_r($var, true);
    }
  } else {
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    if (!extension_loaded('xdebug')) {
      $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
      $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
    }
  }
  if ($echo) {
    echo($output);
    return null;
  } else return $output;
}

//数字千分位处理->舍去法
function sprintfNum($num,$digits=2) {
  $digits_ceil = $digits+1;
  $digits_subtract = $digits-1;
  return sprintf("%.{$digits}f",substr(sprintf("%.{$digits_ceil}f", $num), 0, -$digits_subtract));
}

/**
 * 科学计数法转换为字符串
 * @param $num         科学计数法字符串  如 2.1E-5
 * @param int $double 小数点保留位数 默认5位
 * @return string
 */
function NumToStr($num, $double = 0){
  if(false !== stripos($num, "e")){
    $a = explode("e",strtolower($num));
    return bcmul($a[0], bcpow(10, $a[1], $double), $double);
  }
}