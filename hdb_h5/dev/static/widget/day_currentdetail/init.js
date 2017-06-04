// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/day_currentdetail.css
	//图表
	require('./income_echarts');

//登录弹层
var _poplogin = require('../common/poplogin');
_poplogin.poplogin.init();
// $(function(){
// 	if($('input[name=login_status]').val() == 'true'){
// 		if($(".accountRemin").attr("data-accountRemin")==0){
// 			$(".rollout").removeClass("cRed").attr("href","#")
// 		}
// 	}
// })
