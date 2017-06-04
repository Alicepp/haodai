<?php

defined('APPPATH') or die('Access restricted!');

class qb_Log extends CI_Log {

  private $_level = NULL;
  protected static $_messages = [];

  final public function __destruct() {
    $this->write_log('debug',
      __METHOD__ . PHP_EOL . '页面执行 ' . number_format(microtime(TRUE) - $_SERVER['REQUEST_TIME_FLOAT'], 3)
      . ' sec [' . $_SERVER['REQUEST_URI'] . ']');

    if (!empty(self::$_messages)) {
      $this->write_log($this->_level, implode(NULL, self::$_messages) . PHP_EOL, TRUE);
    }
  }

  protected function _format_line($level, $date, $message, $original = FALSE) {
    if ($original) return parent::_format_line($level, $date, $message);
    else return $message;
  }

  public function write_log($level, $msg, $original = FALSE) {
    if ($original) parent::write_log($level, $msg);
    else {
      if ($this->_enabled === FALSE) return FALSE;

      $level = strtoupper($level);

      if ((!isset($this->_levels[$level]) OR ( $this->_levels[$level] > $this->_threshold))
        && !isset($this->_threshold_array[$this->_levels[$level]])) {
        return FALSE;
      }

      $this->_level = $level;
      self::$_messages[] = $this->_format_line($level, date($this->_date_fmt), $msg, TRUE);
    }
  }

  public function setEnabled($enabled) {
    $this->_enabled = boolval($enabled);
  }

}
