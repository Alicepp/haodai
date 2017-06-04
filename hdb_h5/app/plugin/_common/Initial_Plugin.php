<?php

class Initial_Plugin {

  /**
   * 初始化 Smarty 模板类
   * @param array $params 控制器URI参数
   */
  public function begin($params = array()) {
    $ci = getCiInstance();
    if (LoadClass($class = config_item('subclass_prefix') . 'Smarty', 'core')) {
      $ci->smarty = new $class();

      //ENVIRONMENT !== 'development' && $ci->smarty->compile_check = FALSE;

      $ci->smarty->setDefault_modifiers(array('strip_tags')); //'default:""'

      defined('SMARTY_TPL_DIR') && $ci->smarty->setTplDir(SMARTY_TPL_DIR);
      defined('SMARTY_TPL_EXT') && $ci->smarty->setTplExt(SMARTY_TPL_EXT);

      $ci->smarty->assign('APPPATH', APPPATH);
      $ci->smarty->assign('BASEPATH', BASEPATH);

      $ci->smarty->assign('STATIC_URL', STATIC_URL);
      $ci->smarty->assign('STATIC_DATE', STATIC_DATE);

      defined('LIB_URL') && $ci->smarty->assign('LIB_URL', LIB_URL);

      $ci->smarty->assign('MODULE_NAME', getCssModule());

      $ci->smarty->assign('RESTHUB_URL', RESTHUB_URL);
    }

    //修复 strftime 格式化时中文乱码问题  （影响 %a 等本地化参数！！！）
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') setlocale(LC_TIME, 'eng');
  }

  public function end($params = array()) {
    //
  }

}
