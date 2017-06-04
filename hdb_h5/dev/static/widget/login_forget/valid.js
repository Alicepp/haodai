	var _checkJson=require('../common/checkjson');
	var checkJson = _checkJson.checkJson;
	exports.init=function(){
		var _checkform = require('../common/checkform');
		// 表单校验
		_checkform.hdbValidForm('validForm',function(data,index,validForm){
			//data 为服务器返回的对象
			//index 为checkform返回的当前的表单对象索引值,获取当前validForm表单对象方法为 validForm.eq(index)
			//validForm为当前页面所有的表单对象集合,此对象可以使用validForm插件提供的属性 ,具体属性参考http://validform.rjboy.cn/document.html#validformObject
			//比如要重置当前的表单 validForm.eq(index).resetForm();
			//$(validForm.forms[index]) 这是获取当前表单的jquery对象,可以使用jquery的方法,请勿将validForm对象和jquery对象搞混
			checkJson(data,function(){
				//validForm.eq(index).resetForm();
				//validForm.forms.length为获取当前所有表单的数量
				if(data.result){
					// is_realname==1 已实名
					// is_realname==0 未实名
					if(data.result.is_realname == 1){
						if(validForm.forms.length>index+1){
							$(validForm.forms[index]).removeClass("bounceInRight").addClass("animated bounceOutLeft");
							$(validForm.forms[index+1]).show().addClass("animated bounceInRight")
						}
					}else{
						$(validForm.forms[0]).removeClass("bounceInRight").addClass("animated bounceOutLeft");
						$(validForm.forms[2]).show().addClass("animated bounceInRight");
					}
				}
			})
		});
		/*获取验证码*/
		/*verifyArr 为传入需要验证的input的classname*/
		var verifyArr = ['jsMobilePhone', 'jsImgCode'];
		var _getcode = require('../common/getcode');
		_getcode.comGetCodeFn(verifyArr,function(){
			/*成功获取验证码的回调*/
		});
	}