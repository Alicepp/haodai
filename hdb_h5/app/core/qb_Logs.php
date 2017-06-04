<?php

defined('APPPATH') or die('Access restricted!');

class qb_Logs {

  private $_prefix = NULL;
  private static $_instance = NULL;
  //
  protected static $_sockets = [];

  private function __construct() {
    $this->_prefix = rtrim(config_item('variable_prefix'), '_-') ?: 'unknown';
  }

  final public function __destruct() {
    foreach (self::$_sockets as $stream) {
      $stream && fclose($stream);
    }
  }

  final public static function getInstance() {
    isset(self::$_instance) || self::$_instance = new self();

    return self::$_instance;
  }

  private function _sendto($address, $data) {
    if (isset(self::$_sockets[$address])) $stream = self::$_sockets[$address];
    else {
      $stream = @stream_socket_client($address, $errno, $errmsg, 0.3); //fsockopen

      if ($stream) {
        if (function_exists('socket_import_stream') && stripos($address, 'tcp') === 0) {
          $socket = socket_import_stream($stream);
          //socket_set_option($socket, SOL_SOCKET, SO_KEEPALIVE, 1);
          socket_set_option($socket, SOL_TCP, TCP_NODELAY, 1);
        }
        //stream_set_blocking($stream, 0);
        stream_set_write_buffer($stream, 0);
      }
      else errorLog(__METHOD__ . "\nConnent Error: $errmsg [$address]");

      self::$_sockets[$address] = $stream;
    }

    if ($stream) {
      $len = @fwrite($stream, $data); //stream_socket_sendto  stream_socket_recvfrom
      $len === strlen($data) || errorLog(__METHOD__ . "\nSend Error.");

      $read = [$stream];
      $write = $error = [];
      if (stream_select($read, $write, $error, 0)) fread($stream, 1);
    }
  }

  public function sendTcp($ip, $content, $level = 'info') {
    $port = 514;
    sscanf($ip, '%[^:]:%d', $ip, $port);

    $prefix = '{{' . $this->_prefix . '_PHP}}';
    //$data = $prefix . str_replace(PHP_EOL, PHP_EOL . $prefix, $content); //514
    $data = $prefix . '<log>' . $content . '</log>'; //516

    $this->_sendto('tcp://' . $ip . ':' . $port, $data . PHP_EOL);
  }

  public function sendTelnet($ip, $content, $level = 'info') {
    $port = 2222;
    sscanf($ip, '%[^:]:%d', $ip, $port);

    $data = array('subject' => $this->_prefix, 'text' => rtrim($content), 'level' => $level);
    $data = array('cmd' => 'log', 'data' => $data, 'time' => time());
    $data = jsonencode($data);

    $this->_sendto('tcp://' . $ip . ':' . $port, $data . PHP_EOL);
  }

}
