//define(function(require,exports){
	var calculators = require('../common/calculators');
    exports.init = function(){
        // 预计收益
        calculators.calFn();

        // 加载预计收益验证
        exports.anticipatedIncomeFn();
	};

	// 加载预计收益验证
	exports.anticipatedIncomeFn = function(){
       // 投资金额改变，清空预期收益
       var timer='';
        $('.jsAnIncomeBtn').on('click', function(event) { 
            var calMoney = $(".jsBuyMoney").val(),nCalMoney;
            if (calMoney) {
                nCalMoney = Number(calMoney);
            }
            if(isNaN(nCalMoney) || /^\./.test(nCalMoney)){
                $('.jsFbg').html('请输入正确的投标金额').fadeIn("fast");
                clearTimeout(timer);
                timer = setTimeout(function(){
                    $('.jsFbg').fadeOut("slow");
                },4000);
            }
            else if (nCalMoney*1 % 100!==0) { // 投标金额为100的倍数
               $('.jsFbg').html('投标金额为100的倍数').fadeIn("fast");
               clearTimeout(timer);
               timer = setTimeout(function(){
                    $('.jsFbg').fadeOut("slow");
               },4000);
            }else{
                // 预计收益
                calculators.calFn();
            }
            $(document).one("click", function(){
                $('.jsFbg').hide();
            });
            event.stopPropagation();
        });

        $('.jsFbg').on("click", function(e){
            e.stopPropagation();
        });
	}
//})