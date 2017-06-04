/**
 * Created by lxm on 16/10/27.
 */
// define(function(require,exports) {
    exports.pageLoading=function(arg) {
        //三个点的样式
        //if (arg) {
        //    $('.page-spinner-mask').remove();
        //    $('body').append('<div class="page-spinner-mask"><div class="page-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>');
        //} else {
        //    if($('.page-spinner-mask').length>0){
        //        $('.page-spinner-mask').remove();
        //    }
        //}
        //微信样式
        if(arg){
            $('.page-spinner-mask').remove();
            $('body').append('<div class="page-spinner-mask"><div class="weui-toast"> ' +
                '<i class="weui-loading weui-icon_toast"></i>' +
                ' <p class="weui-toast__content">数据加载中</p> ' +
                '</div></div>')
        }else {
            if($('.page-spinner-mask').length>0){
                $('.page-spinner-mask').fadeOut('fast');
                setTimeout(function(){
                    $('.page-spinner-mask').remove();
                },500)
            }
        }
    }
// })