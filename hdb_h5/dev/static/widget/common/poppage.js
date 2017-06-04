// define(function(require, exports){

	exports.popPage = function(mainMod){
		$('body').delegate('.J_popPageBtn','click',function(e){
			var $page = $('.J_popPage[data-name="'+$(this).data('name')+'"]');
			$page.show().addClass('animated slideInRight');

			// 系统消息
			if($('.noticeDetailDiv').length){
				$('.jsTitleAllCon').html($(this).find('.titleAllCon').html());
				$('.jsPublishDate').html($(this).find('.publishDate').html());
				$('.jsAllContent').html($(this).find('.titleAllCon').attr('allContent'));
			}

			e.preventDefault();
		});

		$('.J_popPageBack').click(function(){
			$(this).parents('.J_popPage').addClass('animated slideOutRight');
			$('.J_mainView').show();
			$("html,body").stop().animate({scrollTop:0}, 100);
		});

		/*动画结束 webkitTransitionEnd*/
	    $('body').on('animationend webkitAnimationEnd', '.slideInRight', function(){
			$(this).removeClass('animated slideInRight');
			$('.J_mainView').hide();
	    });

	    $('body').on('animationend webkitAnimationEnd', '.slideOutRight', function(){
			$(this).removeClass('animated slideOutRight').hide();
	    });

	    $('body').on('animationend webkitAnimationEnd', '.shake', function(){
			$(this).removeClass('animated shake');
	    });
	}

// });