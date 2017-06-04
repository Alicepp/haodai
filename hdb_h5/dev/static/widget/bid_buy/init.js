// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/bid_buy.css
// ##====请求vue
// @require ../../lib/vue
// 表单校验
var timer;
// 调用弹框
var _customDialog = require('../common/customDialog');
var customDialog = _customDialog.customDialog;

var _qb_ajax = require('../common/qb_ajax');
var _checkjson = require('../common/checkjson');
var ajax=_qb_ajax.qbAjax,
    checkJson=_checkjson.checkJson,
	checkDialogCall=_checkjson.checkDialogCall;
var _checkform = require('../common/checkform');
	_checkform.hdbValidForm('projectBidForm',function(data,index,validForm){
	//data 为服务器返回的对象
	//index 为checkform返回的当前的表单对象索引值,获取当前validForm表单对象方法为 validForm.eq(index)
	//validForm为当前页面所有的表单对象集合,此对象可以使用validForm插件提供的属性 ,具体属性参考http://validform.rjboy.cn/document.html#validformObject
	//比如要重置当前的表单 validForm.eq(index).resetForm();
	//$(validForm.forms[index]) 这是获取当前表单的jquery对象,可以使用jquery的方法,请勿将validForm对象和jquery对象搞混
	var dialog = false;
	if(data.status == API_SUCCESS){  //成功
    	dialog = {
			setValue: {   
				title: "",  
				html: true, 
				text: '<div class="success-cont"><div class="success-img invest-img"></div><p>恭喜您投标成功！</p><div class="sub-cont">本次投标金额已打败<span class="cRed"> '+data.result.ratio+' </span>用户</div></div>',
				customClass: 'pop-success',
				showCancelButton: true,
				showConfirmButton: true,
				confirmButtonText: "查看记录",
				cancelButtonText: "知道了",
				closeOnConfirm: false,
				closeOnCancel: false
			}, 
			callback: [function(isConfirm){
				var url = data.result.url;
				if (isConfirm) {
					checkDialogCall(url)
				} else {
					checkDialogCall()
				}
			}]
		}
    }
	checkJson(data,function(){
	}, dialog);
});

// 校验
var vue = new Vue({
	el:'.vueTpl',
	data:{
		money:'',
		coupon_code:'',
		moneyTxt:'输入金额后系统自动勾选最优方案'
	},
	watch:{
		money:'checkCoupon'
	},
	methods:{
		allPut:function(event){
			// 出借金额
			var _amout=$('.jsAmount').attr('amount');
			// 账户余额
			var _usableAmount=$('.jsUsableAmount').attr('amount');
			// 投标时账户余额处理为100的整倍数
			_amout = Math.floor(_amout / 100)*100;
			_usableAmount = Math.floor(_usableAmount / 100)*100;

            // 账户余额不能大于出借金额
            if(_usableAmount > _amout){
            	this.money = _amout;
            }else if(_usableAmount == 0){
    //         	customDialog({},{
	   //          	setValue: {   
				// 		title: "",  
				// 		html: true, 
				// 		text: '<p>余额不足，请先充值！</p>',
				// 		showCancelButton: true,
				// 		showConfirmButton: true,
				// 		confirmButtonText: "去充值",
				// 		cancelButtonText: "知道了",
				// 		closeOnConfirm: false,
				// 		closeOnCancel: true
				// 	}, 
				// 	callback: [function(isConfirm){
				// 		var url = '/my/cashvalue/recharge';
				// 		if(isConfirm){
				// 			checkDialogCall(url);
				// 		}else{
				// 			checkDialogCall();
				// 		}
				// 	}]
				// });
	
				// 产品需求去掉去充值按钮
				customDialog({},{
					setValue: {
						title: "",  
						html: true, 
						text: '<p>余额不足，请先充值！</p>',
						confirmButtonText: "知道了"
					}, 
					callback: [function(){  
						window.location.reload();  // 刷新当前页面
					}]
				});
            }
            else{
            	// 赋值给投入金额文本框
            	this.money = _usableAmount;	
            }
		},
		// 可用优惠券
		checkCoupon:function(curVal,oldVal){
			if(curVal!=oldVal){
				clearTimeout(timer);
				timer=setTimeout(function(){
					ajax({
						url:$('input[name=couponUrl]').val(),
						type:'post',
						data:{
							money:curVal
						},
						success:function(data){
							checkJson(data,function(){
								if(vue.money==''){
									vue.moneyTxt='输入金额后系统自动勾选最优方案';
									vue.coupon_code = '';
									$('.jsPopPage').removeClass('J_popPageBtn');
									$('.jsrArrow').addClass('none');
								}else{
									if(data.result==''){
										vue.moneyTxt = '无可用优惠券';
										vue.coupon_code = '';
										$('.jsPopPage').removeClass('J_popPageBtn');
										$('.jsrArrow').addClass('none');
									}else{
										vue.moneyTxt = '返现'+data.result.resourceAmount+'元';
										vue.coupon_code = data.result.resourceCode;
										$('.jsPopPage').addClass('J_popPageBtn');
										$('.jsrArrow').removeClass('none');

										// 优惠券默认选中当前可用
										$('.jsCouponWrap li').each(function(i,n){
											if($(this).find('.jsAmountTxt').html() == data.result.resourceAmount){
												$('.jsCouponWrap li .jsDot').addClass('none');
												$(this).find('.jsDot').removeClass('none');
												return false;
											}
										});
									}
								}
							})
						}
					});
				},600);
			}
		},
		// 选择优惠券点击
		select_coupon_list:function(event){
			var $html=$(event.currentTarget);
			$('.jsCouponWrap li .jsDot').addClass('none');
			$html.find('.jsDot').removeClass('none');

			// 优惠券code
			var _coupon_code = $html.find('input[name=code]').val();
			vue.coupon_code = _coupon_code;

			// 投资文字
			var _amountTxt = $html.find('.jsAmountTxt').html();
			vue.moneyTxt = '返现'+_amountTxt+'元';
		}
	}
});

// 选择优惠券
var _poppage = require('../common/poppage');
_poppage.popPage();

// 判断是否实名
var _isRealName = require('../common/is_real_name');
_isRealName.isRealNameFn();