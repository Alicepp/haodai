// ##====请求jquery
// @require ../../lib/jquery
	function animateCicle(){
		// 进度动画
		$('.J_circle').each(function(){
			var $svg = $(this);
			var pre = $svg.data('pre');
			var $progress = $svg.find('.J_preC');
			if(!pre){
				$progress.hide();
			}

			$progress.attr('stroke-dasharray', pre + ' 100');
			$progress.attr('stroke-opacity', '1');

			/*线末端点*/
			if($svg.find('.J_lineEnd').length){
				var deg = 360 * pre / 100;
				//css方式
				var line = $svg.find('.J_lineEnd')[0];
				line.style.webkitTransform = "rotate("+ deg +"deg)";
				line.style.transform = "rotate("+ deg +"deg)";
				if(pre >= 100){
					$(line).on('transitionend webkitTransitionEnd', function(){
						$(this).hide();
					});
				}

				//animateTransform 标签做动画
				//$svg.find('.J_lineEnd .J_animateRotate').attr('to', deg +' 19 19');  
				 
				// 设置transform 属性
				//$svg.find('.J_lineEnd').attr('transform', 'rotate('+ deg +', 19 19)');
			}
		});
	}
	
	animateCicle();

	//$('.J_scrollWrap').on('scroll', animateCicle);

// });