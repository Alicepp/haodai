<?php

defined('BASEPATH') OR exit('No direct script access allowed');

ENVIRONMENT !== 'production' && register_shutdown_function(function () {
    $last_error = error_get_last();
    if (isset($last_error) && defined('LOG_LOCAL') && ($last_error['type'] & (E_ERROR | E_PARSE
      | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING)) && function_exists('sendLog')) {
      $msg = $_SERVER['REQUEST_URI'] . ' [' . $_SERVER['REMOTE_ADDR'] . ']' . PHP_EOL;
      $msg .= '[' . $last_error['type'] . '] ' . $last_error['message'] . PHP_EOL . $last_error['file'] . ' [' . $last_error['line'] . ']';

      sendLog($msg, 'error');
    }
  });
