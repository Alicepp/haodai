// define(function(require,exports){
	var checkform = require('../common/checkform'),
		checkJson=require('../common/checkjson').checkJson,
		judgeArg=require("../common/judgearg").judgeArg,
		timeFn=require('../common/countdown').timeFn;
	exports.comGetCodeFn = function(){
		var newArg=judgeArg(arguments);
		$('.comGetCode').click(function(){
			var $btn = $(this),
				thisForm=$(this).parents("form"),
				$cloneForm,
				newFormName="J_cloneForm";
			/*开始创建一个新的form,来通过checkform的机制来验证*/
			if($("."+newFormName).length == 0){
				thisForm.after('<form class="'+newFormName+' none" ></form>');
			};
			$cloneForm = $("."+newFormName);
			$.each(newArg[2], function(i, item){
				var $ipt = $('.' + item).eq(0);
				if($ipt.length){
					var $formIpt = $cloneForm.find('.' + item);
					if($formIpt.length > 0 ){
						$formIpt.val($ipt.val());
					}else{
						$cloneForm.append($ipt.clone());
					}
				}
			});
			$cloneForm.attr('action', $btn.attr('url'));
			/*创造表单结束*/
			checkform.hdbValidForm(newFormName,function(data,index,validForm){
				checkJson(data,function(){
					thisForm.find('.comGetCode').removeClass('comDisabled').removeAttr('disabled');
					timeFn($btn,'time',60);
					if(newArg[3]) newArg[3]();
				})
			});
			$cloneForm.submit();
		});
	}
// })