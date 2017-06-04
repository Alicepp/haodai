// define(function(require, exports){

	exports.drawCircle = function($canvas){

		$canvas.each(function() {
			// 获取进度数值
			var _this = this;
		
			var process = $(_this).data('process');   //进度

			var processInterval = null;
	        var start = 1,
		        end = process,
		        intervalTime = 500/end;
	   		processInterval = setInterval(function(){
			     //画帧
			     drawProcess(_this, start++, true);
			     if(start > end){
			       clearInterval(processInterval);
			       processInterval = null;
			     }
			   }, intervalTime);
		}); 

		function drawProcess(canvas, process) {
	        // 拿到绘图上下文
	        var context = canvas.getContext('2d');
	        var cWidth = canvas.width;
	        var cHeight = canvas.height;
	        var circleX = cWidth/2;
	        var circleY = cHeight/2;
	        var circleR = circleX-40;
	        /*取颜色*/
	        var bgColor = $(canvas).data('bgcolor');
	        var proColor = $(canvas).data('procolor');
	        //终点是否显示圆
	        var endDot = $(canvas).data('showend');

	      	// 将绘图区域清空
	        context.clearRect(0, 0, cWidth, cHeight);

	        //灰色背景
	        context.beginPath();
	        //context.moveTo(circleX, circleY);
	        context.arc(circleX, circleY, circleR, 0, Math.PI * 2, false);
	        context.lineWidth = 14;
	        context.strokeStyle = bgColor;
	        context.stroke();
	        context.closePath();
	        // 画进度
	        context.beginPath();
	        context.lineWidth = 14;  
	        //context.moveTo(circleX, circleY);
	        context.strokeStyle = proColor;
	        context.arc(circleX, circleY, circleR, -Math.PI/2, Math.PI * (2 * (process-.005) / 100-.5), false);
	        context.stroke();
	        context.closePath();

	        context.beginPath();
	        context.arc(circleX+2, 40, 7, 0, Math.PI * 2, false);
	        context.fillStyle = proColor;
			context.fill();
	        context.closePath();
	        
	        if(endDot && process < 100){
				//移动到终端位置
				var x1 = circleX + (circleR-1) * Math.sin(Math.PI * 2 * process / 100);
				var y1 = circleY - (circleR-1) * Math.cos(Math.PI * 2 * process / 100);
				context.moveTo(x1, y1);
				context.beginPath();
				context.arc(x1, y1,20, 0, Math.PI * 2, true);
				context.fillStyle = proColor;
				context.fill();
				context.closePath();
				context.beginPath();
				context.arc(x1, y1, 10, 0, Math.PI * 2, true);
				context.fillStyle = '#fff';
				context.fill();
	          
	        }
	    }  
	}

// });