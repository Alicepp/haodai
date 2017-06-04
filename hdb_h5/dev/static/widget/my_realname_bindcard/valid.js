
	var _checkjson = require('../common/checkjson');
	var checkJson=_checkjson.checkJson,
		checkDialogCall=_checkjson.checkDialogCall;
	exports.init = function(){
		// 表单校验
		var _checkform = require('../common/checkform');
		_checkform.hdbValidForm('validForm',function(data,index,validForm){
			var dialog = false;
			if(data.status == API_SUCCESS){  //成功
	        	dialog = {
					setValue: {   
						title: "",  
						html: true, 
						text: '<div class="success-cont"><div class="success-img bindcard-img"></div><p>'+ data.message +'</p></div>',
						customClass: 'pop-success',
						showCancelButton: true,
						showConfirmButton: true,
						confirmButtonText: "马上充值",
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
				}
	        }

			checkJson(data,function(){
			}, dialog);
		});


		//银行卡号空格分割
		$('.jsCardNumCheck').on('keyup', function(e) {
            //获取当前光标的位置
            var caret = this.selectionStart;
            //获取当前的value
            var value = this.value.replace(/\D/g, '');
            //从左边沿到坐标之间的空格数
            var sp =  (value.slice(0, caret).match(/\s/g) || []).length;
            //去掉所有空格
           var nospace = value.replace(/\s/g, '');
           //重新插入空格
           var curVal = this.value = nospace.replace(/(\d{4})/g, "$1 ").trim();
           //从左边沿到原坐标之间的空格数
           var curSp = (curVal.slice(0, caret).match(/\s/g) || []).length;
          //修正光标位置
           this.selectionEnd = this.selectionStart = caret + curSp - sp;

	    })
	}