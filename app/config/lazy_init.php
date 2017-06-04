<?php

$lazy['smarty'] = function () {
  if (LoadClass($class = config_item('subclass_prefix') . 'Smarty', 'core')) {
    $smarty = new $class();

    //ENVIRONMENT !== 'development' && $ci->smarty->compile_check = FALSE;

    $smarty->setDefault_modifiers(array('strip_tags')); //'default:""'

    defined('SMARTY_TPL_DIR') && $smarty->setTplDir(SMARTY_TPL_DIR);
    defined('SMARTY_TPL_EXT') && $smarty->setTplExt(SMARTY_TPL_EXT);

    $smarty->assign([
      'APPPATH' => APPPATH,
      'BASEPATH' => BASEPATH,
      'STATIC_URL' => STATIC_URL,
      'STATIC_DATE' => STATIC_DATE,
      'RESTHUB_URL' => RESTHUB_URL,
    ]);

    $smarty->assign('MODULE_NAME', getCssModule());

    defined('LIB_URL') && $smarty->assign('LIB_URL', LIB_URL);

    return $smarty;
  }
  else return NULL;
};
