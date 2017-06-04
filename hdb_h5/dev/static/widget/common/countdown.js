// define(function(require, exports){
	/*倒计时*/
	exports.countdown = function($box, callback){
		var $day = $box.find('.J_day'),
			$hour = $box.find('.J_hour'),
			$mini = $box.find('.J_mini'),
			$sec = $box.find('.J_sec');

		var lefttime = $box.data('time') < 0 ? 0 : $box.data('time');
		var setTime=$box.data('settime');
		
		var leftSec = parseInt(lefttime / 1000);

		showTime();
		if(setTime!=undefined){
			if(setTime!=0){
				if($box.data('showline')){
					$day.html(fillzero('--'));
					$hour.html(fillzero('--'));
					$mini.html(fillzero('--'));
					$sec.html(fillzero('--'));
				}
				return;
			}else {
				if(leftSec <= 0){
					if($box.data('showline')){
						$day.html('00');
						$hour.html('00');
						$mini.html('00');
						$sec.html('00');
					}

					return;
				}
			}
		}


		var timer = setInterval(function(){
				leftSec--;
				showTime();

				if(leftSec <= 0){
					clearInterval(timer);
					//执行回调
					callback && callback();   
				}
			}, 1000);

		//显示dom
		function showTime(){  
			var day = Math.floor(leftSec/86400);
			var hour = Math.floor(leftSec/3600%24);
			var mini = Math.floor(leftSec/60%60);
			var sec = Math.floor(leftSec%60);

			$day.length && $day.html(fillzero(day, 2));
			$hour.length && $hour.html(fillzero(hour, 2));
			$mini.length && $mini.html(fillzero(mini, 2));
			$sec.length && $sec.html(fillzero(sec, 2));
		}

		//个位补0
		function fillzero(num, n){
			var number = num + '';
			while(number.length < n){
				number = '0' + number;
			};
			return number;
		}
	};

	// var _time;  // 定义全局定时器
    // var wait = 60; // 倒计时60s
	exports.timeFn=function(t,time,wait) {
        if(wait == 0) {
        	t.removeClass('comDisabled');
            t.removeAttr('disabled');            
            t.val('获取验证码');
            clearTimeout(time);
            wait = 60;
        }else{
        	t.addClass('comDisabled');
            t.attr('disabled', 'disabled');
            t.val(wait + 's后重新获取');
            wait--;
            if(wait == 0){
                t.removeClass('jsMark');
            }
            time = setTimeout(function(){
                exports.timeFn(t,time,wait);
            },1000);
        }

        if(t.hasClass('jsTime')){
            t.val('获取验证码');
            clearTimeout(time);
        }
	}
// });

