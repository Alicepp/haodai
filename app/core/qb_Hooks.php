<?php

defined('APPPATH') or die('Access restricted!');

class qb_Hooks extends CI_Hooks {

  const RULE = [
    //'pre_system' => '_interface', //!!!!
    'pre_controller' => '_construct', //构造方法之前
    'post_controller_constructor' => '_begin', //控制器之前
    'post_controller' => '_end', //控制器之后
  ];

  private $_path = NULL;
  private $_plugin = NULL;

  public function __construct() {
    parent::__construct();

    if ($this->enabled) {
      if (is_file($config = APPPATH . 'config/plugin.php')) {
        $this->_plugin = require $config;
        $this->_path = trim($this->_plugin['plugin_path'], '/') . '/';
      }
    }
  }

  public function call_hook($which = '') {
    if (!$this->enabled) return FALSE;

    $rule = self::RULE;
    if (isset($rule[$which], $this->_plugin['plugin_modules'][$rule[$which]])) {
      if (isset($this->hooks[$which]) && (!is_array($this->hooks[$which]) || isset($this->hooks[$which]['function']))) {
        $this->hooks[$which] = array($this->hooks[$which]); //匿名函数或单一设置
      }

      $range = array('*'); // * class class/method
      if ($ci = getCiInstance()) {
        $class = ltrim(strtolower($ci->router->directory . $ci->router->class), '/');
        $range = array_merge($range, array($class, $class . '/' . strtolower($ci->router->method)));
      }

      $config = $this->_plugin['plugin_modules'][$rule[$which]];
      foreach ($range as $items) {
        if (empty($config[$items])) continue;

        foreach ($config[$items] as $class => $method) {
          $this->hooks[$which][] = [
            'filepath' => $this->_path . ltrim(dirname($class), '/'),
            'filename' => basename($class) . '.php',
            'function' => $method,
            'class' => basename($class),
          ];
        }
      }
    }

    return parent::call_hook($which);
  }

}

//$hook['pre_controller'] = array(
//  array(
//    'class' => 'MyClass',
//    'function' => 'Myfunction',
//    'filename' => 'Myclass.php',
//    'filepath' => 'hooks',
//    'params' => array('beer', 'wine', 'snacks')
//  ),
//);
