
	exports.init=function(){
		// 总资产金额显示隐藏
    	exports.isTotalAssets();
	}

	// 总资产金额显示隐藏
	exports.isTotalAssets=function(){
		//进入页面判断是否显示
		if(localStorage.eyeShow==="1"||localStorage.eyeShow===undefined){
			$('.showMemony').show();
			$('.hideMemony').hide();
			$(".jsTotalAssetsBtn").removeClass('icon_pwd_hui_hide').addClass('icon_pwd_hui_show');
		}else {
			$('.showMemony').hide();
			$('.hideMemony').show();
			$(".jsTotalAssetsBtn").removeClass('icon_pwd_hui_show').addClass('icon_pwd_hui_hide');
		};
		$('.jsTotalAssetsBtn').on('click',function(){
			if($(this).hasClass('icon_pwd_hui_show')){
				localStorage.eyeShow=0;
				$('.showMemony').hide();
				$('.hideMemony').show();
				$(this).removeClass('icon_pwd_hui_show').addClass('icon_pwd_hui_hide');
			}else{
				localStorage.eyeShow=1;
				$(this).removeClass('icon_pwd_hui_hide').addClass('icon_pwd_hui_show');
				$('.showMemony').show();
				$('.hideMemony').hide();
			}
			console.log(localStorage.eyeShow)
		});
		$("a[href='/login/do_loginOff']").click(function(){
			localStorage.eyeShow=1;
		});
	}