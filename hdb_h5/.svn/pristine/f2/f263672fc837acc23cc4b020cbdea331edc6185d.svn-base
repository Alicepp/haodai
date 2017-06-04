<?php

defined('APPPATH') or die('Access restricted!');

class qb_Exceptions extends CI_Exceptions {

  public function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
    if (isEnv('testing|production') && defined('SMARTY_TPL_DIR') && SMARTY_TPL_DIR && !is_cli()) {
      switch ($template) {
        case 'error_404':
        case 'error_general':
          $_config = load_class('Config', 'core');
          $_config->set_item('error_views_path', rtrim(SMARTY_TPL_DIR, '/') . '/_common/errors/');
      }
    }

    return parent::show_error($heading, $message, $template, $status_code);
  }

  //show_404 -> show_error[error_404]
  public function show_404($page = '', $log_error = TRUE) {
    $uri = $_SERVER['REQUEST_URI'];

    $this->showTPL($uri) || parent::show_404($page . ' URI: ' . $uri, $log_error);
  }

  private function showTPL($uri) {
    if (isEnv('development') && defined('FORCE_SHOW_TPL') && FORCE_SHOW_TPL && !is_cli()) {
      $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
      if (strpos($uri, '.') !== FALSE) {
        set_status_header(404);

        return TRUE;
      }

      global $EXT;
      if (empty($ci = getCiInstance())) {
        $EXT->call_hook('pre_controller');

        $class = config_item('subclass_prefix') . 'Controller';
        $ci = class_exists($class, FALSE) ? new $class() : new CI_Controller();
      }

      $method = $ci->router->method; //index
      sscanf(trim($ci->router->default_controller, '/'), '%[^/]/%[^/]', $class, $method);

      $uri = explode('/', $uri);
      empty($uri[0]) and $uri[0] = $class;
      empty($uri[1]) and $uri[1] = $method;
      $uri = implode('/', $uri);

      $EXT->call_hook('post_controller_constructor');

      if (isset($ci->smarty)) {
        debugLog(sprintf('强制渲染模板 [控制器/方法 不存在] %s', $uri));

        $ci->smarty->assign('MODULE_NAME', getCssModule($uri));

        substr($uri, 0, 5) === 'home/' && $uri = substr($uri, 5);
        echo $ci->smarty->view($uri, array('title' => '强制渲染模板'), TRUE);
      }
      else show_error('Smarty not found.');

      $EXT->call_hook('post_controller');

      return TRUE;
    }

    return FALSE;
  }

}
