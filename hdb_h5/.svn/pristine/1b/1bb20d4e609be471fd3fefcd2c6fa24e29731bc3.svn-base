// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/bid_project.css
    // 半环动画 
    require('../common/progress_animate');
      
    //header 菜单
    var menu = require('../common/show_menu');
    menu.showMenu({
        'clickHide': false
    });

    // 项目筛选
    menu.showMenu({
        $menuWrap: $('.J_proFilter'),
        barClass: '.J_filterBar',
        listClass: '.J_filterList',
        isAddMask: true,
        clickHide: false,
        callback: function($item){
            $item.parent().addClass('curr').siblings().removeClass('curr');
        }
    });

    //倒计时
    var countdown = require('../common/countdown');

    $('.J_countTime').each(function(){
    	var $this = $(this);
    	var $item = $this.parents('.J_proItem');
    	countdown.countdown($this, function(){
	        $this.height(0);
	        setTimeout(function(){
	            $item.removeClass('pro-notBegin');
	            $this.remove();
	            $item.find('.J_progressNum').addClass('animated flipInX').html('<p class="num">0%</p>');
	        }, 300);
	    });	
    });

    //下载app
    require('../common/downapp');