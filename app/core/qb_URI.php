<?php

defined('APPPATH') or die('Access restricted!');

class qb_URI extends CI_URI {

  protected function _parse_request_uri() {
    //Apache/2.2 => &#38;
    $_SERVER['REQUEST_URI'] = htmlspecialchars_decode($_SERVER['REQUEST_URI']);

    return parent::_parse_request_uri();
  }

}
