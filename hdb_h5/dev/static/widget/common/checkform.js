// ##====请求jquery
// @require ../../lib/jquery
// ##====请求validForm
// @require ../../lib/validForm
	var judgeArg=require("../common/judgearg").judgeArg;
	var reCharegeMount=API_ENV?(API_ENV==='production'?100:1):100;//当环境为生产环境，则充值最低额度为100，否则为1
	exports.hdbValidForm=function(){
		// ##====请求vue
	    // @require ../../lib/vue
		//如果不传入默认的form名字,就采用默认的class名字;
		var newArg=judgeArg(arguments);
		if(!newArg[0]) newArg[0]="validForm";
		newArg[2]=(typeof newArg[2]=="object")?newArg[2]:{"config":{},"fun":{}};
		var defineObj={
			//参数具体说明请参考 api http://validform.rjboy.cn/document.html
			btnSubmit:"",
			btnReset:"",
			ignoreHidden:false,//当为true时对:hidden的表单元素将不做验证
			dragonfly:false,//当为true时，值为空时不做验证
			tipSweep:true,//在各种tiptype下， 为true时提示信息将只会在表单提交时触发显示，各表单元素blur时不会触发信息提示
			label:".label",//在没有绑定nullmsg时查找要显示的提示文字，默认查找".Validform_label"下的文字
			showAllError:false,//默认为false，true：提交表单时所有错误提示信息都会显示；false：一碰到验证不通过的对象就会停止检测后面的元素，只显示该元素的错误信息
			postonce:true,//为true时，在数据成功提交后，表单将不能再继续提交
			ajaxPost:true,//默认为false，使用ajax方式提交表单数据，将会把数据POST到config方法或表单action属性里设定的地址
			//可用的值有：1、2、3、4和function函数
			tiptype:function(msg,o,cssctl){
				//msg：提示信息;
				//o:{obj:*,type:*,curform:*},
				//obj指向的是当前验证的表单元素（或表单对象，验证全部验证通过，提交表单时o.obj为该表单对象），
				//type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态,
				//curform为当前form对象;
				//cssctl:内置的提示信息样式控制函数，该函数需传入两个参数：显示提示信息的对象 和 当前提示的状态（既形参o中的type）;
				if(!$(o.curform).hasClass("J_cloneForm")){
					var submitHeight=$(o.curform).find("button[type='submit']")[0].clientHeight,//获取当前按钮的位置
						formHeight=$(o.curform)[0].clientHeight,
						errorMsg=formHeight-submitHeight;
					if(o.type==3){
						$(".customForm").remove();
						$(o.curform).append("<div class='animated shake customForm' style='top:"+errorMsg+"px '>"+msg+"</div>")
					}else {
						$(".customForm").remove();
					}
				}else {
					var submitHeight=$(o.curform).prev("form").find("button[type='submit']")[0].clientHeight,//获取当前按钮的位置
						formHeight=$(o.curform).prev("form")[0].clientHeight,
						errorMsg=formHeight-submitHeight;
					if(o.type==3){
						$(".customForm").remove();
						$(o.curform).prev("form").append("<div class='animated shake customForm' style='top:"+errorMsg+"px '>"+msg+"</div>")
					}else {
						$(".customForm").remove();
					}
				}

			},
			datatype:{
				"demo":function(gets,obj,curform,regxp){
					//参数gets是获取到的表单元素值，
					//obj为当前表单元素，
					//curform为当前验证的表单，
					//regxp为内置的一些正则表达式的引用;
					return false;
					//注意return可以返回true 或 false 或 字符串文字，true表示验证通过，返回字符串表示验证失败，字符串作为错误提示显示，返回false则用errmsg或默认的错误提示;
				},
				"rechargeRule": function(gets,obj,curform,regxp){  //充值
					gets=Number(gets);
					if(isNaN(gets) || /^\./.test(gets)){
						return '充值金额只能为数字';
					}
					else if(/^0/.test(gets)){
						return '请输入正确的金额';
					}
					else if(/(\.\d{3,})$/.test(gets)){
						return '请输入2位小数点以内的金额';
					}
					else if(gets < reCharegeMount){
						return '充值金额必须大于等于100元';
					}
				},
				"withdrawRule": function(gets,obj,curform,regxp){  //提现
					var leftNum = Number( $(curform).find('.J_leftSum').text());
					if(isNaN(gets) || /^\./.test(gets)){
						return '请输入正确的提现金额';
					}
					else if(/^0/.test(gets)){
						return '请输入正确的金额';
					}
					else if(/(\.\d{3,})$/.test(gets)){
						return '请输入2位小数点以内的金额';
					}
					else if(gets < 2 || gets > 300000){
						return '提现金额必须大于等于2元，最高限额30万元';
					}else if(gets >  leftNum){
						return '您当前可提现金额为'+ leftNum +'元';
					}
				},
				"buyAmount": function(gets,obj,curform,regxp){  //投标金额
					if(gets == ''){
						return '请输入投标金额';
					}
					else if(isNaN(gets) && /^\./.test(gets) || gets*1 % 100!==0){
						return '请输入100元以上整数金额';
					}
					else if(/^0/.test(gets)){
						return '请输入正确的金额';
					}
				},
				"shiftToAmount": function(gets,obj,curform,regxp){  // 转入
					var _usableAmount=parseInt(curform.find('.jsUsableAmount').attr('amount')); // 账户余额
					var _remaiNum=new Number(curform.find('.jsRemaiNum').attr('remainnum')); // 剩余出借金额
					if(/^0/.test(gets)){
						return '请输入正确的金额';
					}
					else if(gets < 1){
						return '转入金额必须大于1元';
					}
					else if(isNaN(gets) || /^\./.test(gets) || gets*1 % 1!==0){
						return '请输入整数金额';
					}
					else if(gets > _remaiNum){ // 输入金额大于今日可购日息宝余额
						return '剩余可投金额不足';
					}
					else if(gets > _usableAmount){ // 转入金额大于账户余额
						return '账户余额不足';
					}
				},
				"rollOutAmount": function(gets,obj,curform,regxp){  // 转出
					var _remaiNum=parseInt(curform.find('.jsRollOutAmount').attr('remainnum')); // 日息宝余额

					if(isNaN(gets)){
						return '转出金额只能为数字';
					}
					else if(isNaN(gets) || gets*1 % 1!==0 || /\./.test(gets)){
						return '请输入整数金额';
					}
					else if(gets == 0){
						return '请正确输入转出金额';
					}
					else if(gets > _remaiNum){ // 转出金额大于日息宝余额
						return '余额不足';
					}
				},
				"verifyChecked":function(gets,obj){
					if(!obj.is(':checked')){
						return false;
					}
				},
				"passWordRecheck":function(gets,obj,curform,regxp){
					var recheckClass=$(obj).attr("data-recheck");
					if(recheckClass) {
						if($("."+recheckClass).val()!=gets){
							return false;
						}
					}
				},
				"cardNumCheck": function(gets){
					var cardNum = gets.replace(/\s+/g, "");
					if(! /^\d{15,19}$/.test(cardNum)){
						return '请输入15-19位银行卡号';
					}
				}
			},
			usePlugin:{
				swfupload:{},
				datepicker:{},
				passwordstrength:{},
				jqtransform:{
					selector:"select,input"
				}
			},
			beforeCheck:function(curform){
			},
			beforeSubmit:function(curform){
				if($('.jsRollOutAmount').length > 0){
					var _newMoney=$('.jsRollOutAmount').val().replace(/\b(0+)/gi,'');
					$('input[name=money]').val(_newMoney);
				}

				var passWordCrypt=$(curform).find(".passWordCrypt"),
						passWordName=[],
						Cpass=[],
						newPassWordInput=$(curform).find(".newPassWordInput");

				//在表单提交执行验证之前执行的函数，curform参数是当前表单对象。
				//这里明确return false的话将不会继续执行验证操作;

				//if(passWordCrypt.length>0){
				//	// ##====请求jsencrypt
	    			//// @require ../../lib/jsencrypt
				//	var encrypt = new JSEncrypt();
				//	encrypt.setPublicKey(API_PWD);
				//	//解决点击提交按钮 密码框瞬间出现更多字符串的问题
				//	for(var i=0;i<passWordCrypt.length;i++){
				//		Cpass[i]=$(passWordCrypt[i]).val();
				//		if($(passWordCrypt[i]).attr("name")!=""){
				//			passWordName[i]=$(passWordCrypt[i]).attr("name");
				//		};
				//		if($(newPassWordInput[i]).length>0){
				//			$(newPassWordInput[i]).val(encrypt.encrypt(Cpass[i]));
				//		}else {
				//			$(curform).append('<input class="newPassWordInput" style="position: fixed;left: -1000rem;" name="'+passWordName[i]+'" value="'+encrypt.encrypt(Cpass[i])+'">')
				//		}
				//		$(passWordCrypt[i]).attr("name","");
				//	}
                //
				//}
				//$('.eyePwd').prop('type','password');
				//$("#Validform_msg").hide();

				////在验证成功后，表单提交前执行的函数，curform参数是当前表单对象。
				////这里明确return false的话表单将不会提交;
				var ajax=require('../common/qb_ajax').qbAjax,
						action=$(curform).attr("action"),
						thisIndex,i=0;
				if(_form.forms.length>1){
					for(;i<_form.forms.length;i++){
						if(_form.forms[i].offsetTop===curform[0].offsetTop){
							thisIndex=i;
						}
					}
				}
				if(newArg[1]==true){
					if(newArg[3]) newArg[3](thisIndex,_form);
				}else {
					var formData=curform.serialize();
					ajax({
						type:"post",
						url:action,
						data:formData,
						success:function(data){
							//data= {message: "333", url: "/my/password/manage", data: "修改成功",status:API_SUCCESS};
							if(newArg[3]) newArg[3](data,thisIndex,_form);
						}
					});
				}
				return false;
			}
		};
		defineObj=$.extend({},defineObj,newArg[2].config);
		var _form = $("."+newArg[0]).Validform(defineObj);
		exports._form=_form;
		//修改默认的通过验证规则提醒
		_form.tipmsg.r=" ";
		//其他可修改的提示信息
		//{
		//	tit:"提示信息",
		//	w:{
		//		"*":"不能为空！",
		//		"*6-16":"请填写6到16位任意字符！",
		//		"n":"请填写数字！",
		//		"n6-16":"请填写6到16位数字！",
		//		"s":"不能输入特殊字符！",
		//		"s6-18":"请填写6到18位字符！",
		//		"p":"请填写邮政编码！",
		//		"m":"请填写手机号码！",
		//		"e":"邮箱地址格式不对！",
		//		"url":"请填写网址！"
		//  },
		//	def:"请填写正确信息！",
		//	undef:"datatype未定义！",
		//	reck:"两次输入的内容不一致！",
		//	r:"通过信息验证！",
		//	c:"正在检测信息…",
		//	s:"请{填写|选择}{0|信息}！",
		//	v:"所填信息没有经过验证，请稍后…",
		//	p:"正在提交数据…"
		//};
		//给表单验证增加规则
		for(k in newArg[2].fun){
			_form[k](newArg[2].fun[k])
		}
		_form.addRule([
			{
				ele:".jsUserName",
				datatype:/^[\u4e00-\u9fa5·]{2,30}$/,
				errormsg:'请输入正确的姓名',
				nullmsg:'请输入姓名'
			},
			{
				ele:".jsMobilePhone",
				datatype:'/^0{0,1}(13|14|15|17|18)[0-9]{9}$/',
				errormsg:'请输入正确的手机号',
				nullmsg:'您尚未输入手机号'
			},
			{
				ele:".jsUserPhone",
				datatype: /[\w\W]+/,  // *
				errormsg:'请输入正确的用户名/手机号',
				nullmsg:'您尚未输入用户名/手机号'
			},
			{
				ele:".jsPassWord",
				datatype:/^(?=[0-9a-zA-Z!@#$%^*()_.|;:,?/]{6,20}$)/,
				errormsg:'6-20位数字、字母、符号，不允许有空格',
				nullmsg:'请输入登录密码'
			},
			{
				ele:".dealPassWord",
				datatype:/^(?=[0-9a-zA-Z!@#$%^*()_.|;:,?/]{6,20}$)/,
				errormsg:'6-20位数字、字母、符号，不允许有空格',
				nullmsg:'请输入交易密码'
			},
			{
				ele:".jsOldPwd",
				datatype:/^(?=[0-9a-zA-Z!@#$%^*()_.|;:,?/]{6,20}$)/,
				errormsg:'您输入的原密码不正确，请重新输入！',
				nullmsg:'请输入原密码'
			},
			{
				ele:".jsNewPwd",
				datatype:/^(?=[0-9a-zA-Z!@#$%^*()_.|;:,?/]{6,20}$)/,
				errormsg:'您输入的密码格式不正确，请重新输入！',
				nullmsg:'请输入新密码'
			},
			{
				ele:".jsPassWordAffirm",
				datatype:'passWordRecheck',
				errormsg:'两次输入密码不一致！',
				nullmsg:'请再输入一遍密码'
			},
			{
				ele:".jsImgCode",
				datatype: /^[\w\W]{4,8}$/,    //*4-8
				errormsg:'您输入的图形验证码有误',
				nullmsg:'请输入图形验证码'
			},
			{
				ele:".jsSmsCode",
				datatype: /^\d{6}$/,    //n6-6
				errormsg:'您输入的短信验证码有误',
				nullmsg:'请输入短信验证码'
			},
			{
				ele:".jsInputCheck",
				datatype:"verifyChecked",
				nullmsg:'您尚未同意协议'
			},
			{
				ele:".jsIdcardCheck",
				datatype:/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/,
				errormsg:'请输入正确的身份证号码',
				nullmsg:'请输入身份证号码'
			},
			{	/*充值*/
				ele:".jsRecharge",  
				datatype: "rechargeRule",
				//errormsg:'充值金额必须大于等于100元',
				nullmsg:'请输入充值金额'
			},
			{	/*提现*/
				ele:".jsWithdraw",  
				datatype: "withdrawRule",
				nullmsg:'请输入提现金额'
			},
			{	
				ele:".jsBank",  
				datatype: /[\w\W]+/,  // *
				nullmsg:'请选择银行'
			},
			{
				ele:".jsCardNumCheck",
				datatype: "cardNumCheck",  //n15-19
				errormsg:'请输入15-19位银行卡号',
				nullmsg:'请输入15-19位银行卡号'
			},
			{
				ele:".jsInvestmentAmount",
				datatype: "buyAmount",
				errormsg:'投标金额为100的倍数',
				nullmsg:'请输入投标金额'
			},
			{
				ele:".jsRollOutAmount",
				datatype: "rollOutAmount",
				nullmsg:'请输入转出金额',
				errormsg:'请输入整数金额'
			},
			{
				ele:".jsShiftToAmount",
				datatype: "shiftToAmount",
				nullmsg:'请输入转入金额',
				errormsg:'请输入正确的金额'
			},
			{
				ele:".jsFeedback",
				datatype: "*",
				nullmsg:'请输入您的宝贵意见'
			}
		]);

		// 协议框点击验证
		$('.jsInputCheck').click(function(){
			$(".jsInputCheck").blur();
		});
	};
// });