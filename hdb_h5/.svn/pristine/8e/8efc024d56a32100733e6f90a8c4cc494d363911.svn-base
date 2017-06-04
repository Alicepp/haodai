
	var _checkJson=require('../common/checkjson');
	var checkJson = _checkJson.checkJson;
	var checkDialogCall=_checkJson.checkDialogCall;
	exports.init = function(){

		$('.validForm button[type="submit"]').prop('disabled', false);
		// 表单校验
		var _checkform = require('../common/checkform');
		_checkform.hdbValidForm('validForm',function(data,index,validForm){
			checkJson(data,function(){
				var customDialog = require('../common/customDialog').customDialog; // 调用弹框
				customDialog({},{
					setValue: {
						title: "",
						html: true,
						text: '<div class="success-cont"><div class="success-img recharge-img"></div><p>成功充值<span class="cRed"> '+ data.result.money +' </span>元</p></div>',
						customClass: 'pop-success',
						showCancelButton: true,
						showConfirmButton: true,
						confirmButtonText: "去投标",
						cancelButtonText: "知道了",
						closeOnConfirm: false,
						closeOnCancel: false
					},
					callback: [function(isConfirm){
						var url = data.result.url;
						if (isConfirm) {
							checkDialogCall(url);
						} else {
							checkDialogCall("/my/info");
						}
					}]
				});
			});
		});
	}
