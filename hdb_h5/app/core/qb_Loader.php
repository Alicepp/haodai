<?php

defined('APPPATH') or die('Access restricted!');

class qb_Loader extends CI_Loader {

  public function __construct() {
    parent::__construct();
    log_message('info', 'qb_Loader Class Initialized');

    unset($this->_ci_classes);
    $this->_ci_classes = is_loaded();
  }

  /**
   * 根据uri调用其它控制器 (不允许嵌套调用,如非必须,不建议使用)
   * @param string $uri
   * @return boolean 文件不存在时返回false
   */
  public function runController($uri) {
    $segments = explode('/', trim($uri, '/'));

    $dir = NULL;
    $i = count($segments);
    while ($i-- > 0) {
      $class = array_shift($segments);
      if (is_file($file = APPPATH . 'controllers/' . $dir . ucfirst($class) . '.php')) {
        $class = ucfirst($class);
        break;
      }
      elseif (is_dir(APPPATH . 'controllers/' . $dir . $class)) {
        $dir .= $class . '/';
      }
      else return FALSE;
    }
    $method = count($segments) > 0 ? array_shift($segments) : 'index';

    log_message('debug', sprintf('runController: %s->%s %s', $class, $method, json_encode($segments)));

    class_exists($class, FALSE) && show_error($class . ' class exists.');

    require $file;
    $class = new $class();
    method_exists($class, $method) || show_error($method . ' method not found.');

    call_user_func_array(array($class, $method), $segments);
  }

}
