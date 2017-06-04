// tab切换
// ##====请求jquery
// @require ../../lib/jquery
	exports.tabFn=function(title,content){
		$('.'+title).on('click',function(){
			var index=$(this).index();
			$('.'+title).removeClass('active');
			$(this).addClass('active');
			$('.'+content).addClass('hide').eq(index).removeClass('hide');
		})
	}