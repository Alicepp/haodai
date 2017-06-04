<?php

/**
 * Created by PhpStorm.
 * User: songluo
 * Date: 2017/3/3
 * Time: 下午1:54
 */

//设置缓存白名单
class Plugin_Output_Cache {

  public function begin($params = array()){
    $ci = getCiInstance();
    $class = strtolower($ci->router->directory . $ci->router->class);
    $method = strtolower($ci->router->method);

    $cache_template = [
      'financial_plan/agreement'=>['service'],
      'automatic_bid'=>['explain','agreement'],
      'bid'=>['staticintro'],
      'day'=>['faq','question'],
      'help'=>['*'],//缓存所有
      'home'=>['service','safe','about','more'],
      'register'=>['register_success','register_error','agreement'],
    ];

    if(array_key_exists($class,$cache_template) && (in_array('*',$cache_template[$class]) || in_array($method,$cache_template[$class]))){
      $ci->output->cache(OUTPUT_CACHE_TIME_LONG);
    }

  }
}