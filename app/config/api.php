<?php

define('STATIC_DATE', '20170417'); //每次提测时更新

define('SMARTY_TPL_DIR', APPPATH . '../view');
define('SMARTY_TPL_EXT', 'htm');

define('REDIS_PREFIX', ''); //每个项目单独申请

define('DOMAIN', '//' . $_SERVER['HTTP_HOST'] . '/');

define('API_DEBUG', isset($_SERVER['HTTP_QBAPIDEBUG']) ? boolval($_SERVER['HTTP_QBAPIDEBUG']) : FALSE);
define('FETCH_DUMMY',
  ENVIRONMENT === 'development' && isset($_SERVER['HTTP_QBFETCHDUMMY']) ? boolval($_SERVER['HTTP_QBFETCHDUMMY']) : FALSE);

switch (ENVIRONMENT) {
  case 'development': //开发
    define('FORCE_SHOW_TPL', TRUE);
    define('LOG_LOCAL', 'dev-lib.f2e.li');
    //define('LOG_SYS', '192.168.1.161:516');
    define('REDIS_HOST', 'http://dev-apis.qianbao.com');

    define('RESTHUB_URL', 'https://dev-apis.qianbao.com');

    define('STATIC_URL', '/static/dev');

    define('LIB_URL', 'http://dev-lib.f2e.li');

    break;

  case 'testing': //测试
    define('FORCE_SHOW_TPL', FALSE);
    define('LOG_LOCAL', 'dev-lib.f2e.li');
    //define('LOG_SYS', '192.168.1.161:516');
    define('REDIS_HOST', 'http://sit-apis.qianbao.com');

    define('RESTHUB_URL', 'https://sit-apis.qianbao.com');

    define('STATIC_URL', '/static/release');

    define('LIB_URL', 'http://dev-lib.f2e.li');

    break;

  case 'production': //生产
    define('FORCE_SHOW_TPL', FALSE);
    //define('LOG_SYS', 'logs.qianbao.com:516');
    define('REDIS_HOST', 'http://apis.qianbao.com');

    define('RESTHUB_URL', 'https://apis.qianbao.com');

    define('STATIC_URL', '//static.qianbao.com/m');

    define('LIB_URL', 'http://lib01.qianbao.com');

    break;
}

define('API_FAILURE_MSG', '网络异常，请稍后再试');

//接口常量  必须9开头
define('API_SUCCESS', '20000107');
define('API_FAILURE', '99999999');

//TOKEN 失效
define('API_TIMEOUT', '99999998');
define('API_DENIED', '99999990');

//密码框密钥
define('JSPHP_PWD_PUBLIC', '');
define('JSPHP_PWD_PRIVKEY', '');
