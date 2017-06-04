// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/my_subsidiary_payment_records.css
    // 和收支明细公用一个js
    var _list = require('../common/list');
    var upDown=_list.upDown,
        ajaxObj={
            ajax:{
                url:$('.jsList').attr('url'),
                type:"post",
                data:{
                    pageIndex:1,
                    type:2
                }
            }
        };
    upDown(ajaxObj);
    $(".nav-wid").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        ajaxObj.ajax.data.type=$(this).attr("type");
        upDown(ajaxObj);
    })