// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/my_realname_bindcard.css

/*选择银行*/
require('./selectbank');
/*表单验证*/
var _ = require('./valid');
_.init();
/*获取验证码*/
var verifyArr = ['jsUserName', 'jsIdcardCheck', 'jsBank', 'jsBankCode', 'jsCardNumCheck', 'jsMobilePhone', 'jsRecharge'];
var _getcode = require('../common/getcode');
_getcode.comGetCodeFn(verifyArr);

/*充值说明*/
var _pop = require('../common/poppage');
_pop.popPage();