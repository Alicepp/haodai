<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Proxy extends qb_Controller {

  public function json() { //模拟JSON数据
    $url = implode('/', func_get_args());

    $model = new qb_Model();
    switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
      case 'GET':
        $result = $model->fetchGet($url, $_GET);

        break;

      case 'POST':
        $result = $model->fetchPost($url, $_POST);

        break;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo jsonencode($result);
  }

  public function clean($uri = '') { //清除控制器缓存
    if ($uri === '') { //首页
      $method = 'index';
      sscanf(trim($this->router->default_controller, '/'), '%[^/]/%[^/]', $class, $method);

      $uri[] = '';
      $uri[] = $class;
      $uri[] = $class . '/' . $method;
    } else $uri = (array)$uri;

    foreach ($uri as $value) {
      $result = $this->output->delete_cache('/' . str_replace('_', '/', $value));

      echo '清除指定缓存 ' . ($result ? '成功' : '失败') . ' [' . $value . ']<br/>';
    }
  }

}
