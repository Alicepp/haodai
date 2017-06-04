	var _checkJson = require('../common/checkjson');
	var checkJson=_checkJson.checkJson;
	exports.init=function(){
		// 表单校验
		var _checkform = require('../common/checkform');
		_checkform.hdbValidForm('feedBackForm',function(data,index,validForm){
			//data 为服务器返回的对象
			//index 为checkform返回的当前的表单对象索引值,获取当前validForm表单对象方法为 validForm.eq(index)
			//validForm为当前页面所有的表单对象集合,此对象可以使用validForm插件提供的属性 ,具体属性参考http://validform.rjboy.cn/document.html#validformObject
			//比如要重置当前的表单 validForm.eq(index).resetForm();
			//$(validForm.forms[index]) 这是获取当前表单的jquery对象,可以使用jquery的方法,请勿将validForm对象和jquery对象搞混
			checkJson(data,function(){
				
			})
		});
	}