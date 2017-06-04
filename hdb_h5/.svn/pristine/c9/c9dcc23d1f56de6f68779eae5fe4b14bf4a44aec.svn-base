<?php

class ResponseException extends RuntimeException {

  protected $_data;

  public function __construct($message = '', $code = 0, $data = NULL, Exception $previous = NULL) {
    parent::__construct($message, $code, $previous);

    $this->setData($data);
  }

  public function setData($data = NULL) {
    $this->_data = $data;

    return $this;
  }

  public function getData() {
    return $this->_data;
  }

}

class ArgumentException extends InvalidArgumentException {

  protected $_field;

  public function __construct($message = '', $code = 0, $field = NULL, Exception $previous = NULL) {
    parent::__construct($message, $code, $previous);

    $this->setField($field);
  }

  public function setField($field = NULL) {
    $this->_field = $field;

    return $this;
  }

  public function getField() {
    return $this->_field;
  }

}
