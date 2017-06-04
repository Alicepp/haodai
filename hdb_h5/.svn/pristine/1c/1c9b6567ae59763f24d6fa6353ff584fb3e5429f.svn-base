// define(function(require, exports){

	var $map = $('.J_siteMapWrap');
	$('.J_siteMap').click(function(){
		$map.show().addClass('animated fadeInUp');
	});

	$('.J_siteMapWrap').on('click', '.J_closemap', function(){
		$map.addClass('animated fadeOutDown');
	});

	$('body').on('click', function(e){
		if($map.is(':visible') && $(e.target).closest('.J_siteMap').length == 0 && $(e.target).closest('.J_siteMapWrap').length == 0 ){
			$map.addClass('animated fadeOutDown');
		}
	});

	$('body').on('animationend webkitAnimationEnd', '.fadeInUp', function(){
		$(this).removeClass('animated fadeInUp');
    });
    $('body').on('animationend webkitAnimationEnd', '.fadeOutDown', function(){
		$(this).removeClass('animated fadeOutDown');
		$('.J_siteMapWrap').hide();
    });
// });