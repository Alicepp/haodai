<?php

define('STATIC_DATE', '20161222'); //每次提测时更新

define('SMARTY_TPL_DIR', APPPATH . '../view');
define('SMARTY_TPL_EXT', 'htm');

define('REDIS_PREFIX', '2750'); //每个项目单独申请

define('DOMAIN', '//' . $_SERVER['HTTP_HOST'] . '/');

define('API_DEBUG', isset($_SERVER['HTTP_QBAPIDEBUG']) ? boolval($_SERVER['HTTP_QBAPIDEBUG']) : FALSE);
define('FETCH_DUMMY',
  ENVIRONMENT === 'development' && isset($_SERVER['HTTP_QBFETCHDUMMY']) ? boolval($_SERVER['HTTP_QBFETCHDUMMY']) : FALSE);

switch (ENVIRONMENT) {
  case 'development': //开发
    define('FORCE_SHOW_TPL', TRUE);
    define('LOG_LOCAL', 'dev-lib.f2e.li');
    //define('LOG_SYS', '192.168.1.161:516');
    define('REDIS_HOST', 'https://dev-apis.qianbao.com');

    define('HDB_URL_v1', 'https://hdbapp.qianbao.com/mobile');
    define('HDB_URL_v2', 'http://dev-apis.qianbao.com/hdb/v1');
    define('QB_URL', 'http://sit-apis-qb.f2e.li');
    define('RESTHUB_URL', 'http://dev-apis.qianbao.com');
    define('HDB_PC_URL', '//testqbfptest.haodaibao.com');
    define('API_KEY', 'HDBNDk0OWJmY2MtNTUzMC00NzcyLWI3NzEtODlhOTkzODMwNmUwM');

    define('STATIC_URL', '/static');

    define('LIB_URL', 'http://dev-lib.f2e.li');

    break;

  case 'testing': //测试
    define('FORCE_SHOW_TPL', FALSE);
    define('LOG_LOCAL', 'dev-lib.f2e.li');
    //define('LOG_SYS', '192.168.1.161:516');
    define('REDIS_HOST', 'https://sit-apis.qianbao.com');

    define('HDB_URL_v1', 'https://hdbapp.qianbao.com/mobile');
    define('HDB_URL_v2', 'http://sit-apis.qianbao.com/hdb/v1');
    define('QB_URL', 'http://sit-apis-qb.f2e.li');
    define('RESTHUB_URL', 'http://sit-apis.qianbao.com');
    define('HDB_PC_URL', '//teststabletest.haodaibao.com');
    define('API_KEY', 'HDBNDk0OWJmY2MtNTUzMC00NzcyLWI3NzEtODlhOTkzODMwNmUwM');

    define('STATIC_URL', '/static');

    define('LIB_URL', 'http://test-lib.f2e.li');

    break;

  case 'production': //生产
    define('FORCE_SHOW_TPL', FALSE);
    //define('LOG_SYS', 'logs.qianbao.com:516');
    define('REDIS_HOST', 'https://apis.qianbao.com');

    define('HDB_URL_v1', 'https://hdbapp.qianbao.com/mobile');
    define('HDB_URL_v2', 'http://apis.qianbao.com/hdb/v1');
    define('QB_URL', 'http://api01.qianbao.com');
    define('RESTHUB_URL', 'http://apis.qianbao.com');
    define('HDB_PC_URL', '//www.haodaibao.com');
    define('API_KEY', 'PURSEFPZDczMThlODItNTAzMC00ZDhmLTg0YjUtMzYwMGNmMWU1Mjlh');

    define('STATIC_URL', '//static.haodaibao.com/h5');

    define('LIB_URL', 'http://lib01.haodaibao.com');

    break;
}
//app下载地址
define('APP_DOWNLOAD_URL', 'http://a.app.qq.com/o/simple.jsp?pkgname=com.haodaibao.android');

define('API_FAILURE_MSG', '网络异常，请稍后再试');
define('API_LOGIN_FAILURE_MSG', '登录状态失效,为保证账户安全,请重新登录');
define('OUTPUT_CACHE_TIME_LONG', 1);//缓存时间单位（分）

//接口常量  必须9开头
define('API_SUCCESS', '0000');
define('API_FAILURE', '9999');

//TOKEN 失效
define('API_TIMEOUT', '9998');
define('API_DENIED', '9990');

//密码框密钥
define('JSPHP_PWD_PUBLIC', 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC4+XNm/53z7COmo4KeAHx4w80Gij/cMhUF2MFz4sFsra5a7CtvKDShaH9gppofqENqkHU39avsc7qIMhZy9hMgRE7TMRVSwk1kKqM4V7BSPlsEefsJRn2NvyrusT5TlPBgum0nEuE2ujpEl+1/3ASvvEMuBSMWnN62/FBeVtprmwIDAQAB');
define('JSPHP_PWD_PRIVKEY', 'MIICXQIBAAKBgQC4+XNm/53z7COmo4KeAHx4w80Gij/cMhUF2MFz4sFsra5a7CtvKDShaH9gppofqENqkHU39avsc7qIMhZy9hMgRE7TMRVSwk1kKqM4V7BSPlsEefsJRn2NvyrusT5TlPBgum0nEuE2ujpEl+1/3ASvvEMuBSMWnN62/FBeVtprmwIDAQABAoGAI8e05/aIEjfaSZCVYoTLjvyo+xvg8HILmP7tpMH3ElOiR1opqK3JSHOTOBnh9D+zErjww6tU3z8flNrDdudcmWvP0cMkEWjWwoo/ZPiJw7PdbCQNEYRUsaRTRHGi8M2LKQhizMVf5E9yctXoaNczmzJfQSSeVeSy4L1fzthQHnkCQQDqsb/kvwuvzsb2pFfrv0gf171BGjplICe/J74j9wn4D4rsGuG5QsS9jwRfeTmISIOySu8plJWXMaF1tIq1JvOvAkEAycQ4VbsOFiVzHllSRu5jpZvGAtWrc6/KbLgINMKaj5yMJa8pDf+/HxWgnqa+pq7lgGYSEd52TGZ6ESD7WdTF1QJBANjBsekiSTjmUPwabNleoM5IApLOFoCvYgY6PnIZBywDrtrBORhRgkNQg0lqPKiR0JAqhRhyEnbBb822ISWOBCECQD6vSLVR5SF6Rxaxt380Bx9nkxBEY/0QK3q1fk8t+qkNgPkgP0gLIcy9gmZn9amBgunuTETi8avw1pHFxnF1SnUCQQDlSvYtfg0loXcDZBByShslqm3bYxgzgukZPqct5kAbLS3Kp694W6Iqr4u9z5hmdXwzckZ9f/qtfYQl/7WlyjsG');

/* ----------------------钱包状态码----------------------------- */
define('API_QB_SUCCESS', '00000000');
define('API_HDB_SUCCESS', '200');

/* ------------------------自定义状态码----------------------------- */
define('API_PWD_FAILURE_OVERRUN', '9001'); //密码错误次数超限
define('API_ALERT_TWO_BUTTON', '9002'); //新弹窗样式-》弹窗显示两个点击按钮
define('API_ALERT_ONE_BUTTON', '9003'); //新弹窗样式-》弹窗显示一个点击按钮

/* ----------------------H5是否调用APP桥----------------------------- */
define('API_H5_BRIDGE_APP', '9200');

