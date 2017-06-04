<?php

class Limit_Plugin {

  /**
   * 生产环境禁止运行的方法，$config['action_disabled'] 配置
   */
  public function begin($params = array()) {
    $ci = getCiInstance();
    $class = strtolower($ci->router->class);
    $method = strtolower($ci->router->method);

    if (isEnv('production') && ($actions = array_change_case(config_item('action_disabled')))) {
      (in_array($class, $actions) || (isset($actions[$class]) && in_array($method,
          array_change_value_case($actions[$class])))) && show_error('method disabled.');
    }
  }

}
