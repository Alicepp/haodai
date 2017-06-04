/**
 *  Class: eyes
 *  Author: lvpeipei
 *  Date: 2016/10/13.
 *  Description:密码显示或隐藏
 */
// define(function(require, exports) {
    exports.eyes=function(){
        $('.eye').on('click',function(){
            if($(this).hasClass('icon_pwd_hui_hide')){
                $(this).removeClass('icon_pwd_hui_hide').addClass('icon_pwd_hui_show');
                $(this).parents(".con-b").siblings().find("input").prop({
                  type: 'text'
                })
            }else{
                $(this).removeClass('icon_pwd_hui_show').addClass('icon_pwd_hui_hide');
                $(this).parents(".con-b").siblings().find("input").prop({
                  type: 'password'
                })
            }
        })
    }
// })