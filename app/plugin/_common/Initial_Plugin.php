<?php

class Initial_Plugin {

  /**
   * 初始化
   * @param array $params 控制器URI参数
   */
  public function begin($params = array()) {
    if ($ci = getCiInstance()) {
      $ci->output->parse_exec_vars = FALSE;
    }

    //修复 strftime 格式化时中文乱码问题  （影响 %a 等本地化参数！！！）
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') setlocale(LC_TIME, 'eng');
  }

  public function end($params = array()) {
    //
  }

}
