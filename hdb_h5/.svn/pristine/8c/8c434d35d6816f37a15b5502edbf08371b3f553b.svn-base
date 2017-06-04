    //默认的弹框js
    exports.customDialog=function(){
        // ##====请求sweetalert
        // @require ../../lib/sweetalert
        var json=arguments[0];
        // console.log(json);
        if(arguments.length==1){

            var msg=arguments[0].message;
            swal({
                    title:'',
                    text:msg ,
                    //confirmButtonColor: btnCorlor,
                    confirmButtonText: "确定"
                },function() {
                    if(json.url){
                        window.location.href = json.url;
                    }
                }
            )
        };
        if(arguments.length==2&&typeof arguments[1]=="object"){
            if(!arguments[1].setValue.showCancelButton){
                setTimeout(function(){
                    $(".sa-confirm-button-container").css("width","100%");
                },500)
            }else {
                setTimeout(function(){
                    $(".sa-confirm-button-container").css("width","50%");
                },30)
            }
            swal(arguments[1].setValue,arguments[1].callback[0]);
        }else if(arguments.length==2&&typeof arguments[1]!="object"){
            swal("dialog传入参数格式错误");
        };
        setTimeout(function(){
            if($(".sa-button-container .cancel").is(":hidden")){
                $(".sa-confirm-button-container").css("width","100%");
            }

        },500)
    }