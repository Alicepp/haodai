/**
 *  Class: init
 *  Author: liyn
 *  Date: 2016/11/17.
 *  Description:投标记录
 */
// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/my_subsidiary_bids_records.css
	// 和收支明细公用一个js
    var _list = require('../common/list');
    var menu = require('../common/show_menu'),
        upDown=_list.upDown,
        ajaxObj={
            ajax:{
                url:$('.jsList').attr('url'),
                type:"post",
                data:{
                    pageIndex:1,
                    type:3
                }
            }
        };
    upDown(ajaxObj);
    // 项目筛选
    menu.showMenu({
        $menuWrap: $('.J_proFilter'),
        barClass: '.J_filterBar',
        listClass: '.J_filterList',
        isAddMask: true,
        callback: function($item){
            ajaxObj.ajax.data.type=$item.attr("type");
            upDown(ajaxObj);
            $item.parent().addClass('curr').siblings().removeClass('curr');

        }
    });