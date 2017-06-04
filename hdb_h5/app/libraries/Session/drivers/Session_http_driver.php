<?php

class Session_http_driver extends CI_Session_driver {

  const LOCK_TIME = 60 * 3;

  protected $_redis = NULL;
  protected $_key_prefix = 'qb_session:';
  protected $_key_exists = FALSE;
  //
  protected $_lock_key = NULL;

  public function __construct($params) {
    parent::__construct($params);

    if (empty($this->_config['save_path']) || (sscanf($this->_config['save_path'], '%[^#]#prefix=%s', $host, $prefix) !==
      2)) {
      throw new Exception('Session: No Redis save path configured.');
    }

    LoadClass('qb_Redis', 'core') && $this->_redis = new qb_Redis($host, $prefix);
    if (empty($this->_redis)) throw new Exception('qb_Redis not found.');

    if ($this->_config['match_ip'] === TRUE) $this->_key_prefix .= $_SERVER['REMOTE_ADDR'] . ':';
  }

  protected function _get_lock($session_id) {
    $lock_key = $this->_key_prefix . $session_id . ':lock';

    if ($this->_lock_key === $lock_key) {
      return $this->_redis->expire($lock_key, self::LOCK_TIME);
    }

    for ($i = 0; $i < 30; $i++) {
      if (($lock_ttl = $this->_redis->ttl($lock_key)) > 0) { //检查同会话
        sleep(1);

        continue;
      }

      if ($this->_redis->setex($lock_key, self::LOCK_TIME, time())) { //!!!!!!
        $this->_lock_key = $lock_key;
        $this->_session_id = $session_id;

        break;
      }
      else {
        errorLog('Session: Error while trying to obtain lock for ' . $lock_key);

        return FALSE;
      }
    }

    if ($i === 30) {
      errorLog('Session: Unable to obtain lock for ' . $lock_key . ' after 30 attempts, aborting.');

      return FALSE;
    }
    elseif ($lock_ttl === -1) debugLog('debug', 'Session: Lock for ' . $lock_key . ' had no TTL, overriding.');

    return TRUE;
  }

  protected function _release_lock() {
    if (isset($this->_lock_key)) {
      if (!$this->_redis->delete($this->_lock_key)) {
        errorLog('Session: Error while trying to free lock for ' . $this->_lock_key);

        return FALSE;
      }

      $this->_lock_key = NULL;
      $this->_session_id = NULL;
    }

    return TRUE;
  }

  public function open($save_path, $name) {
    return $this->_config['save_path'] ? $this->_success : $this->_fail();
  }

  public function close() {
    return $this->_release_lock() ? $this->_success : $this->_fail();
  }

  public function read($session_id) {
    if (isEnv('development|testing')) {
      debugLog(__METHOD__ . " $session_id");
    }

    if ($this->_get_lock($session_id)) {
      $session_data = $this->_redis->get($this->_key_prefix . $session_id);
      if (is_string($session_data)) $this->_key_exists = TRUE;
      else $session_data = '';

      $this->_fingerprint = md5($session_data);

      return $session_data;
    }

    return $this->_fail();
  }

  public function write($session_id, $session_data) {
    if (isEnv('development|testing')) {
      debugLog(__METHOD__ . " $session_id $session_data");
    }

    if (isset($this->_lock_key)) {
      if ($session_id !== $this->_session_id) { //session_regenerate_id
        if (!$this->_release_lock() || !$this->_get_lock($session_id)) {
          return $this->_fail();
        }
      }
      else $this->_redis->expire($this->_lock_key, self::LOCK_TIME);

      if ($this->_fingerprint !== ($fingerprint = md5($session_data))) {
        if ($this->_redis->setex($this->_key_prefix . $session_id, $this->_config['expiration'], $session_data)) {
          $this->_fingerprint = $fingerprint;

          return $this->_success;
        }

        return $this->_fail();
      }

      return !$this->_key_exists || $this->_redis->expire($this->_key_prefix . $session_id, $this->_config['expiration'])
          ? $this->_success : $this->_fail();
    }

    return $this->_fail();
  }

  public function destroy($session_id) {
    if (isset($this->_lock_key)) {
      if (($result = $this->_redis->delete($this->_key_prefix . $session_id)) !== 1) {
        debugLog('Session: Redis::delete() expected to return 1, got ' . $result . ' instead.');
      }

      $this->_cookie_destroy();

      return $this->_success;
    }

    return $this->_fail();
  }

  public function gc($maxlifetime) {
    return $this->_success;
  }

}
