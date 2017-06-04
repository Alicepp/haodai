/**
 * Created by lxm on 2016/11/18.
 */

    // 和收支明细公用一个js
    //var _upDown = require('../common/list');
    var ajaxObj={
            ajax:{
                url:'/my/coupon/getCouponList',
                type:"get",
                data:{
                    status:0
                }
            }
        };
    //upDown(ajaxObj);

// ##====请求jquery
// @require ../../lib/jquery
// ##====请求vue
// @require ../../lib/vue
require('../common/pullToRefresh');
Date.prototype.format = function(format) {
    var date = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
        }
    }
    return format;
};
var _qb_ajax = require('../common/qb_ajax');
var _checkjson = require('../common/checkjson');
var ajax=_qb_ajax.qbAjax,
    checkJson=_checkjson.checkJson,newObj={},
    upDown={
        up: function (args) {
            var $target = $(args.container) || $('.dropload');
            $target[0] && $target.pullToRefresh().on("pull-to-refresh", function (YY) {
                args.callback ? args.callback.call(this, $target) : (function () {
                    var timer = setTimeout(function () {
                        clearTimeout(timer);
                        $target.pullToRefreshDone();
                    }, 500);
                })()
            });
        },
        down:function(cb){
            $("body").downToRefresh(cb)
        }
    },vue = new Vue({
        el: '.appwrap',
        data: {
            list:[],
            status:0,
            hasData:true    //首屏是否有数据
        },
        methods: {
            getList:function(b,cb){
                //if(newObj){
                //    newObj.ajax.data.pageIndex=vue.pageNum;
                //};
                ajax({
                    needLoading:b,//如何不需要默认的加载等待动画，可以加上此参数
                    url:"/my/coupon/getCouponList",
                    type:"get",
                    data:{
                        status:this.status
                    },
                    success:function(data){
                        checkJson(data,function(){
                            //第一次加载如果没有数据则显示无数据样式
                            if(data.data.length==0){
                                vue.hasData=false;
                                vue.list=[];
                                $(".nocouponswrap").show();
                            }else {
                                vue.list=data.data;
                                vue.hasData=true;
                                Vue.nextTick(function () {
                                    $(window).scrollTop(0);
                                });
                                //$(".nocouponswrap").hide();
                            };
                            if (cb) cb(data);
                        })
                    }
                })
            },
            changeList:function(event,num){
                console.log(event)
                this.status=num;
                $(event.target).addClass("active").siblings().removeClass("active");
                this.getList();
            },
            computedClass: function(item) {
                return item.status == 2 ? 'ysx' :'fxq';
            },
            couputedEnabledText: function(status) {
                if (status == 0) {
                    return '未使用';
                } else if (status == 2) {
                    return '已失效';
                }else if (status == 4) {
                    return '已使用';
                }
            },
            reset:function(){
                vue.pageNum=1;
                vue.lastPage=false;
                $(".lastext").hide();
                $(".btmRefreshing").show();
            }
        }
    });
Vue.filter("date", function(value) {
    return new Date(value).format('yyyy.MM.dd');
});

(function(obj){

    $.extend(true,newObj,obj);
    vue.reset();
    vue.pageNum=obj.ajax.data.pageIndex;
    //vue.list=[];
    $(".btmDIv").hide();
    // 第一屏加载
    vue.getList(true,function(data){
        $(".dropload-layer").attr("data-refreshing","0");
        Vue.nextTick(function () {
            if(data.data.length>0&&$(document).height()>$(window).height()){
                $(".btmDIv").show();
            }else {
                $(".btmDIv").hide();
            };
        });
    });
})(ajaxObj)
upDown.up({
    container: $("body"),
    callback: function (me) {
        vue.reset();
        vue.getList(false,function(data){
            setTimeout(function () {
                me.pullToRefreshDone();
            }, 500);
        })
    }
});
