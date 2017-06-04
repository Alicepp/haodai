<?php

defined('APPPATH') or die('Access restricted!');

require(APPPATH . 'libraries/helperReflection.php');

class qb_SiteMap {

  private static $_instance = NULL;

  //@siteback url/$1[ => .+(\d{8}).*]
  const SITEBACK = '\s*\*\s*@siteback\s+([^\s]+)(?:\s+=>\s+([^\s]+))?';

  private function __construct() {
    //
  }

  public static function getBackUrl($classname, $methodname) {
    isset(self::$_instance) || self::$_instance = new self();

    $ref = new helperReflection($classname);
    $result = self::$_instance->_parse($ref->getMethodComment($methodname));
    $result === FALSE && $result = self::$_instance->_parse($ref->getClassComment());

    return $result;
  }

  protected function _parse($config) {
    if (preg_match('#^' . self::SITEBACK . '$#im', str_replace(["\r\n", "\r"], "\n", $config), $rule) === 1) {
      if (strpos($rule[1], '$') === FALSE) return $rule[1];
      else {
        if (isset($rule[2][0])) {
          //$rule[2] = str_replace([':any', ':num'], ['[^\r\n]+', '[0-9]+'], $rule[2]);
          $result = preg_replace('#^' . $rule[2] . '$#i', $rule[1], $_SERVER['REQUEST_URI'], -1, $count);

          return $count ? $result : FALSE;
        }
        else throw new InvalidArgumentException('返回参数配置异常.');
      }
    }
    else return FALSE;
  }

}
