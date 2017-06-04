// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/register_index.css

var _valid = require('./valid');
    _valid.init();

// 系统消息详情弹框
var _poppage = require('../common/poppage');
_poppage.popPage();

//图形验证码
require('../common/graphcode');
