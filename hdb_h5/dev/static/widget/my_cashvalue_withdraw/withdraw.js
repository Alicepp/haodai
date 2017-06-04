
	var _checkJson = require('../common/checkjson'), 
		_validForm = require('../common/checkform'),
		_customDialog = require('../common/customDialog');

	var checkJson = _checkJson.checkJson,
		checkDialogCall=_checkJson.checkDialogCall,
		validForm = _validForm.hdbValidForm,
		customDialog = _customDialog.customDialog;
	//全部提现
	$('.J_getAllLeft').click(function(){
		var money = $('.J_leftSum').html().replace(/,/g, '');
		$('.jsWithdraw').val(money).focus();
	});
	//手续费
	var withdrawNum;
	var timer = null;
	$('.jsWithdraw').focus(function(){
		var $this = $(this);
		timer = setInterval(function(){
			var num = $.trim($this.val());
			if (withdrawNum != num) {
				var factorage = num < 50000 ? '1.00' : '5.00';
				$('.J_factorage').html(factorage);
	            withdrawNum = num;
	        }
		}, 200);
		
	});
	$('.jsWithdraw').blur(function(){
		clearInterval(timer);
	});

	$('.validForm button[type="submit"]').prop('disabled', false);
	validForm('validForm', function(data, index, form){
		/*未设置交易密码弹窗*/
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
					if(data.url && data.url.indexOf("://") == -1){
						window.location.href = data.url;
					}
				}]
			}
		checkJson(data, function(){
			//已设置交易密码
			var $form = $(form.forms);
			var $hideDielog = $('.J_dialogHtml');
			//获取取现金额和手续费
			var getNum = $form.find('.jsWithdraw').val();
			$hideDielog.find('.J_getNum').html( Number(getNum).toFixed(2) ); 
			$hideDielog.find('.J_getNumHidd').val( Number(getNum).toFixed(2) ); 
			$hideDielog.find('.J_fact').html( $('.J_factorage').html() ); 
			var withdrawHtml = $hideDielog.html();

			customDialog({},{
				setValue: {   
					title: "输入交易密码",   
					html: true,
					text: withdrawHtml,
					customClass: 'withdrawDialog',
					showConfirmButton: true,
					confirmButtonText: '确定',
					closeOnConfirm: false
				}, 
				callback: [function(){
					/*提现*/
					widthdraw();
				}]
			});
			//关闭
			$('.J_closeWithdraw').click(function(){
				swal.close();
			});

		}, dialogObj);
	});

	
	function widthdraw(){
		validForm('widthdrawForm', function(result,i,validForm){
			checkJson(result,function(){
				var customDialog = require('../common/customDialog').customDialog; // 调用弹框
				customDialog({},{
					setValue: {
						title: "",
						html: true,
						text: '<div class="success-cont"><div class="success-img withdraw-img"></div><p>成功提现<span class="cRed"> '+ result.result.money +' </span>元</p></div>',
						customClass: 'pop-success',
						showCancelButton: true,
						showConfirmButton: true,
						confirmButtonText: "查看收支明细",
						cancelButtonText: "知道了",
						closeOnConfirm: false,
						closeOnCancel: false
					},
					callback: [function(isConfirm){
						var url = result.result.url;
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
	