exports.verifyLogin = function(fromPop, callback){ //fromPop: 是否来自弹层
		var _checkjson = require('../common/checkjson');
		var checkJson=_checkjson.checkJson;
		var vform = require('../common/checkform');
	    // 表单校验
	    vform.hdbValidForm('loginForm',function(data,index,validForm){
	        //data 为服务器返回的对象
	        //index 为checkform返回的当前的表单对象索引值,获取当前validForm表单对象方法为 validForm.eq(index)
	        //validForm为当前页面所有的表单对象集合,此对象可以使用validForm插件提供的属性 ,具体属性参考http://validform.rjboy.cn/document.html#validformObject
	        //比如要重置当前的表单 validForm.eq(index).resetForm();
	        //$(validForm.forms[index]) 这是获取当前表单的jquery对象,可以使用jquery的方法,请勿将validForm对象和jquery对象搞混
	        
	        /*连续输错3次密码*/
	        var fn = callback;
	        if(data.status == '9001'){
	        	if(data.message){
        			var _customDialog = require("../common/customDialog");
		        	var customDialog = _customDialog.customDialog;
		        	customDialog(data);
	        	}

	        	fn = function(){
	        		$graphCode = $('.loginForm .J_graphCodeMod');
	        		$graphCode.show();
	        		$graphCode.find('.J_imgcodeIpt').attr('name', 'identifyCode').removeAttr('ignore');
	        		$graphCode.find('.J_graphCode').attr('src', data.result.image);

	        		var $name = $('.loginForm .J_loginName');
	        		var verifyName = $.trim($name.val());
	        		$name.on('blur', function(){
	        			var newName = $.trim($(this).val());
	        			if(verifyName != newName){  //切换登录名
	        				$graphCode.hide();
	        				$graphCode.find('.J_imgcodeIpt').attr('name', '').attr('ignore', 'ignore');
	        				$('.loginForm .customForm').remove();
	        			}
	        		});
	        	}
	        }

	        if(fromPop && data.status == API_SUCCESS){
	        	data.url = '';
	        }

	        checkJson(data,function(){
	        	fn && fn();
	        });
	    });

	    // 密码可见
	    var eye=require("../common/eyes");
	    eye.eyes();

	    //图形验证码
		require('../common/graphcode');

	}