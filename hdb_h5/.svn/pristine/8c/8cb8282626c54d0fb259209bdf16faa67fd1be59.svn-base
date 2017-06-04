// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/bid_regulardetail.css
/*圆环*/
require('../common/progress_animate');
//倒计时
var countdown = require('../common/countdown');
countdown.countdown($('.J_countTime'), function(){
	var $btn = $('.J_buyBtn');
	$btn.attr('href', $btn.data('url')).removeClass('disable');
}); 

//登录弹层
var _poplogin = require('../common/poplogin');
_poplogin.poplogin.init();