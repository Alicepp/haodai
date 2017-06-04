// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/my_info.css

// header 菜单
var menu = require('../common/show_menu');
menu.showMenu({
    'clickHide': false
});

//登录弹层
var _poplogin = require('../common/poplogin');
_poplogin.poplogin.init();

// 我的页面验证
var _valid = require('./valid');
_valid.init();

// 判断是否实名
var _isRealName = require('../common/is_real_name');
_isRealName.isRealNameFn();

//下载app
require('../common/downapp');