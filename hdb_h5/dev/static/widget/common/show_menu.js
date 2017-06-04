// ##====请求jquery
// @require ../../lib/jquery
	exports.showMenu = function(options){
		
		var defaults = {
			$menuWrap:$('.J_menuWrap'),  //菜单容器
			barClass:'.J_menu',          //点击图标
            listClass:'.J_menuList',     //菜单列表
            itemClass:'.J_item',         //列表项
			showClassName:'active',      //显示隐藏class
			animateClass: [],            //列表动画['zoomInDown', 'zoomInUp'] 第1个是弹出动画 第2个隐藏动画
			clickHide: true,             //点击列表项是否隐藏
			isAddMask:false,
			callback:null
		};
		var opt = $.extend(defaults, options);
		var $menuWrap = opt.$menuWrap, $list = $menuWrap.find(opt.listClass);
		var $pageWrap = $('body');

		$menuWrap.on('click', opt.barClass, function(e){
			if($menuWrap.hasClass(opt.showClassName)){
				hideMenu();
			}else{
				$list.show()
				$menuWrap.addClass(opt.showClassName);
				opt.isAddMask && $pageWrap.append('<div class="filter-mask J_filterMask"></div>');  //添加蒙层
			}
		});
		//点击列表项
		$menuWrap.on('click', opt.itemClass, function(){
			opt.clickHide && hideMenu();
			if(typeof opt.callback == 'function'){
				opt.callback($(this));
			}
		});
		$pageWrap.on('click', function(e){
			if($(e.target).closest(opt.barClass).length == 0 && $(e.target).closest(opt.listClass).length == 0){
				hideMenu();
			}
		});
		function hideMenu(){
			$list.hide();
			$menuWrap.removeClass(opt.showClassName);
			opt.isAddMask && $pageWrap.find('.J_filterMask').remove();
		}

	};

// });