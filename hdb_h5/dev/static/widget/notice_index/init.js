// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/notice_index.css

// 调用公用分页vue
var _list = require('../common/list');
var upDown = _list.upDown,
	ajaxObj={
		ajax:{
			url:$(".nav-wid.active").attr('url'),
			type:'post',
			data:{
				pageIndex:1
			}
		}
	};
upDown(ajaxObj);

// tab切换
$(".nav-wid").click(function(){
	var _index=$(this).index();
    $(this).addClass("active").siblings().removeClass("active");
    ajaxObj.ajax.url = $(this).attr('url');

    $('.jsList').hide().eq(_index).show();
    upDown(ajaxObj);
});

// 系统消息详情弹框
var _poppage = require('../common/poppage');
_poppage.popPage();

// 公用ajax
var _qb_ajax = require('../common/qb_ajax');
var _checkjson = require('../common/checkjson');
var ajax=_qb_ajax.qbAjax,
    checkJson=_checkjson.checkJson;

// 详情页弹框禁止滑动
$('.jsList').delegate('.jsMsgListBtn','click',function(){
	document.body.style.overflow='hidden';
	var el = $(this);
	if(el.find('.jsIsRead').val() == 0){
		ajax({
			url:'/notice/readSysMsg/'+el.attr('id'),
			type:'post',
			success:function(data){   // 返回0000 表示已读
				checkJson(data,function(){
					el.find('.jsDot').addClass('none');
					el.find('.jsTitleCon').addClass('gray');
				});
			}
		});
	}
});

$(".noticeDetailDiv").delegate('.J_popPageBack','click',function(){
	document.body.style.overflow="";
})