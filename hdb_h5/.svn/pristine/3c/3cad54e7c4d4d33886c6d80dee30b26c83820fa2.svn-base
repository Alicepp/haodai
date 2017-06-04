/**
 *  Class: 
 *  Author: liyn
 *  Date: 2016/11/21.
 *  Description:转出
 */
 // ##====请求css
// @require ../../css/common/common.css
// @require ../../css/bid_roll_out.css

	// 表单校验
	var checkJson=require('../common/checkjson').checkJson,
		ajax=require('../common/qb_ajax').qbAjax,
		validForm=require('../common/checkform').hdbValidForm,
		checkDialogCall=require('../common/checkjson').checkDialogCall;
		validForm('projectBidForm',function(data,index,validForm){
		//data 为服务器返回的对象
		//index 为checkform返回的当前的表单对象索引值,获取当前validForm表单对象方法为 validForm.eq(index)
		//validForm为当前页面所有的表单对象集合,此对象可以使用validForm插件提供的属性 ,具体属性参考http://validform.rjboy.cn/document.html#validformObject
		//比如要重置当前的表单 validForm.eq(index).resetForm();
		//$(validForm.forms[index]) 这是获取当前表单的jquery对象,可以使用jquery的方法,请勿将validForm对象和jquery对象搞混

			if(data.status=="9999"){
				var dialogObj = {
					setValue: {
						title: "温馨提示",
						customClass: 'warmPrompt',
						text: data.message,
						showCancelButton: true,
						showConfirmButton: true,
						confirmButtonText: "去设置",
						cancelButtonText: "取消",
						closeOnConfirm: false
					},
					callback: [function(){
						checkDialogCall(data.url);
					}]
				};
			};
		checkJson(data,function(){
			var customDialog = require('../common/customDialog').customDialog;
			var pwdDialogHtml = $('.J_dialogHtml');
			var _money = $('input[name=money]').val();  // 过滤金额开头的0
			/*未设置交易密码弹窗*/
			if(data.status=="9003"){

				customDialog({},{
					setValue: {
						title: "温馨提示",
						customClass: 'dialogF26',
						text: data.message,
						showCancelButton: false,
						showConfirmButton: true,
						confirmButtonText: "确定",
						closeOnConfirm: false
					},
					callback: [function(){
						checkDialogCall(data.url);
					}]
				});
				return
			};
			if(data.status=="9002"){
				customDialog({},{
					setValue: {
						title: "温馨提示",
						customClass: 'dialogF26',
						text: data.message,
						showCancelButton: true,
						showConfirmButton: true,
						confirmButtonText: "去实名",
						cancelButtonText: "取消",
						closeOnConfirm: false
					},
					callback: [function(){
						checkDialogCall(data.url);
					}]
				});
				return
			};
			$('.jsMoney').html(_money+'.00');
			// 转出金额
			$('.jsMoneyHidden').val(_money);
			customDialog({},{
				setValue: {   
					title: "输入交易密码",
					html: true,
					text: pwdDialogHtml.html(),
					customClass: 'withdrawDialog',
					confirmButtonText: '确定',
					showConfirmButton: true,
					closeOnConfirm: false
				}, 
				callback: [function(){
					rollout();
				}]
			});
			$('.J_closeWithdraw').click(function(){
				swal.close();
			});
		}, dialogObj);
	});

	function rollout(){
		validForm('pwdForm', function(result,i,validForm){
			checkJson(result,function(){
				var customDialog = require('../common/customDialog').customDialog; // 调用弹框
				customDialog({},{
						setValue: {
							title: "",
							html: true,
							text: '<div class="success-cont"><div class="success-img rollout-img"></div><p>成功转出<span class="cRed"> '+ result.result.money +' </span>元</p></div>',
							customClass: 'pop-success',
							showCancelButton: true,
							showConfirmButton: true,
							confirmButtonText: "再次转出",
								cancelButtonText: "知道了",
							closeOnConfirm: false,
							closeOnCancel: false
						},
						callback: [function(isConfirm){
							var url = result.result.url;
							if (isConfirm) {
								checkDialogCall();
							} else {
								checkDialogCall()
							}
						}]
				});
			});
		});	
	}

	/*转出说明*/
	var _pop = require('../common/poppage');
	_pop.popPage();