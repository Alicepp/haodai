<?php

class helperRsa {

  private $_pubkey;
  private $_privkey;
  //
  private static $_instance;

  private function __construct() {
    //
  }

  final public static function getInstance($pubkey = '', $privkey = '') {
    isset(self::$_instance) || self::$_instance = new self();

    self::$_instance->setKey($pubkey, $privkey);

    return self::$_instance;
  }

  public function setKey($pubkey, $privkey) {
    //openssl_pkey_get_private('file://private_key.pem');
    $pubkey && $this->_pubkey = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($pubkey, 64) . "-----END PUBLIC KEY-----";
    $privkey && $this->_privkey = "-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split($privkey, 64) . "-----END RSA PRIVATE KEY-----";
  }

  private function _encrypt($data) { // 1024/8-11 => 117
    if (!openssl_public_encrypt($data, $result, $this->_pubkey)) throw new Exception('Unable to encrypt data.');

    return $result;
  }

  private function _decrypt($data) {
    if (openssl_private_decrypt($data, $decrypted, $this->_privkey)) $result = $decrypted;
    else $result = '';

    return $result;
  }

  public static function Encrypt($data, $pubkey = '') {
    $_self = self::getInstance($pubkey, '');

    return base64_encode($_self->_encrypt($data));
  }

  public static function Decrypt($data, $privkey = '') {
    $_self = self::getInstance('', $privkey);

    return $_self->_decrypt(base64_decode($data));
  }

  public static function hexEncrypt($data, $pubkey = '') {
    $_self = self::getInstance($pubkey, '');

    return bin2hex($_self->_encrypt($data));
  }

  public static function hexDecrypt($data, $privkey = '') {
    $_self = self::getInstance('', $privkey);

    return $_self->_decrypt(hex2bin($data)); //pack('H*', $data)
  }

}
