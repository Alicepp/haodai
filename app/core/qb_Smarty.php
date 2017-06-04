<?php

defined('APPPATH') or die('Access restricted!');

require(APPPATH . 'libraries/smarty-3.1.29/Smarty.class.php');

class qb_Smarty extends Smarty {

  private $_url;
  private $_extension = '.tpl';

  public function __construct($template_dir = '', $compile_dir = '', $config_dir = '', $cache_dir = '') {
    parent::__construct();

    if (is_array($template_dir)) {
      foreach ($template_dir as $key => $value) {
        $this->$key = $value;
      }
    }
    else {
      $this->template_dir = $template_dir ? $template_dir : APPPATH . 'views';
      $this->config_dir = $config_dir ? $config_dir : APPPATH . 'config';
      $this->compile_dir = $compile_dir ? $compile_dir : APPPATH . '../data/smarty/compile';
      $this->cache_dir = $cache_dir ? $cache_dir : APPPATH . '../data/smarty/cache';
    }

    $this->_url = md5($_SERVER['REQUEST_URI']);

    $this->loadPlugin('smarty_compiler_switch') && $this->registerFilter('post', 'smarty_postfilter_switch');
  }

  private function getTemplate($template) {
    $template = ltrim($template, '/') . (strstr($template, '.') ? '' : $this->_extension);
    $this->templateExists($template) || show_error('template error [' . $template . '].');

    return $template;
  }

  public function isCache($template) {
    return $this->isCached($this->getTemplate($template), $this->_url);
  }

  public function view($template, array $data = array(), $return = FALSE) {
    empty($data) || $this->assign($data);

    $result = $this->fetch($this->getTemplate($template), $this->_url);
    if ($return === FALSE) {
      if ($ci = getCiInstance()) $ci->output->append_output($result);
      else echo $result;

      return;
    }
    else return $result;
  }

  public function setTplDir($directory) {
    $directory && is_dir($directory) && $this->template_dir = $directory;
  }

  public function setTplExt($extension = 'tpl') {
    $extension && $this->_extension = '.' . ltrim($extension, '.');
  }

}
