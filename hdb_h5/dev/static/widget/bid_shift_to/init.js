/**
 *  Class: 
 *  Author: liyn
 *  Date: 2016/11/21.
 *  Description:转入
 */
// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/bid_shift_to.css
// ##====请求vue
// @require ../../lib/vue
	var checkJson=require('../common/checkjson').checkJson, // 表单校验
		checkDialogCall=require('../common/checkjson').checkDialogCall,
    	customDialog = require('../common/customDialog').customDialog; // 调用弹框
	require('../common/checkform').hdbValidForm('projectBidForm',function(data,index,validForm){
		//data 为服务器返回的对象
		//index 为checkform返回的当前的表单对象索引值,获取当前validForm表单对象方法为 validForm.eq(index)
		//validForm为当前页面所有的表单对象集合,此对象可以使用validForm插件提供的属性 ,具体属性参考http://validform.rjboy.cn/document.html#validformObject
		//比如要重置当前的表单 validForm.eq(index).resetForm();
		//$(validForm.forms[index]) 这是获取当前表单的jquery对象,可以使用jquery的方法,请勿将validForm对象和jquery对象搞混
		/*var dialog = false;
		if(data.status == API_SUCCESS){  //成功
        	
        }*/
		checkJson(data,function(){
			if(data.status == 9002){
				customDialog({},{
					setValue: {   
						title: "",   
						text: data.message,
						customClass: 'dialogF26',
						showCancelButton:true,  
						showConfirmButton:true,  
						confirmButtonText:"充值",  
						cancelButtonText:"取消"
					}, 
					callback: [function(){
						window.location.href=data.url;
					}]
				});
			}else if(data.status == 0000){
				customDialog({},{
					setValue: {   
						title: "",  
						html: 'true', 
						text: '<div class="success-cont"><div class="success-img shift-img"></div><p>成功转入<span class="cRed"> '+ data.result.money +' </span>元</p><div class="sub-cont"><span class="cGray">预计收益到账时间:</span><span> '+data.result.expiration_time+' </span></div></div>',
						customClass: 'pop-success',
						showCancelButton: true,
						showConfirmButton: true,
						confirmButtonText: "再次转入",
	  					cancelButtonText: "知道了",
						closeOnConfirm: false,
						closeOnCancel: false
					}, 
					callback: [function(isConfirm){
						var url = data.result.url;
						if (isConfirm) {
							checkDialogCall();
						} else {
							checkDialogCall()
						}
					}]
				});
			}
		});
	});
	//history.replaceState(null, "", "/day/currentdetail");
	// 全部转入
	var vue = new Vue({
		el:'.vueTpl',
		data:{
			money:''
		},
		computed:{
			isRemainNum:function(){
				// 剩余可投金额大于1,按钮可点
				var _remaiNum=parseInt($('.jsRemaiNum').attr('remainnum'));
				if(_remaiNum<1){
					return true;
				}else{
					return false;
				}
			}
		},
		methods:{
			allPut:function(event){
				var _usableAmount=$('.jsUsableAmount').attr('amount'); // 账户余额
				var _remaiNum=$('.jsRemaiNum').attr('remainnum'); // 剩余出借金额
				_usableAmount = Math.floor(_usableAmount); // 取整
				_remaiNum = Math.floor(_remaiNum); // 取整
				if(_usableAmount==0){
					customDialog({message:"余额不足"});
					return;
				};
				if(_remaiNum==0){
					customDialog({message:"目前无可出借金额"});
					return;
				};
				// 账户余额大于出借金额
				if(_usableAmount>_remaiNum){
					this.money = _remaiNum;
				}else{
					this.money = _usableAmount;
				}
			}
		}
	});


	/*转入说明*/
	var _pop = require('../common/poppage');
	_pop.popPage();
    // 判断是否实名
    var _isRealName = require('../common/is_real_name');
    _isRealName.isRealNameFn();