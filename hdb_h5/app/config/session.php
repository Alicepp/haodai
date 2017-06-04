<?php

define('SESSION_HTTP', TRUE);

if (SESSION_HTTP) {
  if (!defined('REDIS_HOST') || !defined('REDIS_PREFIX')) {
    throw new Exception('Session: No Redis configured.');
  }

  get_config([
    'sess_driver' => 'http',
    'sess_save_path' => REDIS_HOST . '#prefix=' .
    rtrim(config_item('variable_prefix'), '_-') . '_PHP-' . REDIS_PREFIX,
  ]);
}