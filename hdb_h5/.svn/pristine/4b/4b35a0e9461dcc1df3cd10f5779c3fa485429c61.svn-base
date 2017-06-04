/**
 * Created by lyn on 2016/12/13.
 */

// 判断是否实名
exports.isRealNameFn=function(){
    // 调用弹框
    var _customDialog = require('../common/customDialog');
    var checkDialogCall=require('../common/checkjson').checkDialogCall;
    var customDialog = _customDialog.customDialog;
    $('.jsIsRNameLink').on('click',function(e){
        // is_realname==1 已实名  is_realname==0 未实名  is_realname==-1 认证中
        var _isRealName=$('.jsIsRealName');
        var _message=_isRealName.attr('message');
        var _url=_isRealName.attr('url');

        // 未实名
        if(_isRealName.val() == '0'){               
            e.preventDefault();
            // 未实名弹框
            customDialog({},{
                setValue: {   
                    title: "",   
                    text: _message,
                    customClass: 'dialogF26',
                    showCancelButton:true,  
                    showConfirmButton:true,  
                    confirmButtonText:"去认证",  
                    cancelButtonText:"取消"
                }, 
                callback: [function(){
                    checkDialogCall(_url);
                }]
            });
        }else if(_isRealName.val() == '-1'){ // 认证中     
            e.preventDefault();
            // 未实名弹框
            customDialog({},{
                setValue: {
                    title: "",
                    text: _message,
                    customClass: 'dialogF26',
                    confirmButtonText:"确定" 
                }, 
                callback: [function(){
                }]
            });
        }
    })
}