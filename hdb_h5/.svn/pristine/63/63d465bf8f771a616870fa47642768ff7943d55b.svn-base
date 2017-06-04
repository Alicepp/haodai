/**
 * Created by lxm on 16/10/26.
 */
// define(function (require, exports) {
    exports.checkJson = function () {
        //去掉等待框
        var _pageLoading = require("../common/pageLoading");
        _pageLoading.pageLoading(false);
        // console.log(arguments[0])
        //可以一共传入4个参数,也可以分别传入0-4个参数,请严格按照顺序传入参数
        //1个参数情况:必须为json,服务器返回的对象
        //2个参数情况:可以为(json,callback)或者(json,dialog),其中dialog为对象格式,传入自定义的dialog的配置
        //dialog参数配置请参考sweetalert官网 http://t4t5.github.io/sweetalert/
        //3个参数情况:可以为(json,callback,true/false)或者(json,callback,dialog)
        //4个参数情况:(json,callback,true/false,dialog)
        //其中第二个布尔类型参数为当为true,则不执行checkjson中的逻辑,直接执行用户自定义的回调,
        //为false的情况为在返回值为成功状态码的情况下执行回调,默认不传为false
        //dialog为自定义的弹窗对象,不传执行默认的弹窗逻辑即customDialog.
        var _customDialog = require("../common/customDialog");
        var customDialog = _customDialog.customDialog,
            json = arguments[0] || {message: "", url: "", data: ""},
            callback = typeof arguments[1]=="function" ? arguments[1]:function(){},
            successStatus = API_SUCCESS,
            commonErrorStatus = API_FAILURE,
            isCustomDialog={a:false,i:""},
            commonHandle = function (json,ob) {
                ob=ob||{a:false,i:""};
                if (json.message&&!ob.a){
                    customDialog(json);
                } else if (json.message&&ob.a){
                    customDialog(json,ob.i);
                }else {
                    if(json.url&&json.url.indexOf("://")==-1)
                        window.location.href = json.url;
                }
            };
        //根据不同的传入参数来执行不同的逻辑
        for(var i=1;i<arguments.length;i++){
            if(typeof arguments[i] == "object"){
                isCustomDialog={a:true,i:arguments[i]};
            };
        };
        if(json.status==successStatus||json.status==commonErrorStatus){
            if (typeof arguments[2] == "boolean") {
                if(arguments[2])
                    callback();
                else
                    commonHandle(json,isCustomDialog);
                if(json.status==successStatus) callback();
            }else {
                commonHandle(json,isCustomDialog);
                if(json.status==successStatus) callback();
            }
        }else {
            callback();
        }

    };
    exports.checkDialogCall=function(url){
        var locationUrl=window.location.href;
        var f=url?(url.indexOf("?") === -1?"?":"&"):(locationUrl.indexOf("?") === -1?"?":"&");
        var param="incompatible=1";
        if(url){
            //考虑服务器安全
            if(url.indexOf("://") === -1){
                window.location.href = url+f+param;
            }else {
                window.location.href = "/";
            }
        }else {
            location.reload();
        }
    };
// })