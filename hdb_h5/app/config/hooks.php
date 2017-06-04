<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | This file lets you define "hooks" to extend CI without hacking the core
  | files.  Please see the user guide for info:
  |
  |	https://codeigniter.com/user_guide/general/hooks.html
  |
 */

$hook['pre_system'][] = function() {
  Date_default_timezone_set('PRC');

  foreach (['api'] as $item) {
    file_exists($file = APPPATH . 'config/' . $item . '.php') && require $file;
  }

  foreach (['Common', 'Exception', 'Logs'] as $item) {
    file_exists($file = APPPATH . 'core/' . config_item('subclass_prefix') . $item . '.php') && require $file;
  }

  defined('LOG_LOCAL') && ENVIRONMENT !== 'production' && SetErrorHandler();

  if (!isInstalled($file = APPPATH . '../data/install.lock', STATIC_DATE)) {
    //Hooks -> Config -> Log    /data/smarty/compile
    foreach (['log_path' => 0755, 'sess_save_path' => 0700, 'cache_path' => 0700] as $key => $item) {
      if ($key = config_item($key)) {
        is_dir($key) || @mkdir($key, $item, TRUE);

        is_really_writable($key) || showError('临时目录不存在或不可写.');
      }
    }

    foreach (config_item('php_extension') as $item) {
      extension_loaded($item) || showError('缺少扩展 ' . $item . ' .');
    }

    if (ini_get('opcache.enable') == 1) { //opcache_reset()
      $status = opcache_get_status();
      if (!empty($status['scripts'])) {
        foreach ($status['scripts'] as $item) {
          strpos($item['full_path'], FCPATH) === 0 && opcache_invalidate($item['full_path'], TRUE);
        }
      }
    }

    file_put_contents($file, time());
  }
};
