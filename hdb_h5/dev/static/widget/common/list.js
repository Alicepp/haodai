/**
 * Created by lxm on 2016/11/21.
 */
// ##====请求jquery
// @require ../../lib/jquery
// ##====请求vue
// @require ../../lib/vue
require('../common/pullToRefresh');

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
            el: '.vueTpl',
            data: {
                list:[],
                pageNum:1,      //初始化当前页为第一页
                pageCount:"",    //初始化每一页加载的条数，注意此数据应该是后端返回
                lastPage:false, //是否为最后一页
                hasData:true    //首屏是否有数据
            },
            methods: {
                getList:function(b,cb){
                    if(newObj){
                        newObj.ajax.data.pageIndex=vue.pageNum;
                    };
                    if(!vue.lastPage){
                        ajax({
                            needLoading:b,//如何不需要默认的加载等待动画，可以加上此参数
                            url:newObj.ajax.url,
                            type:newObj.ajax.type,
                            data:newObj.ajax.data,
                            success:function(data){
                                checkJson(data,function(){
                                    //逻辑分为是否为第一页
                                    vue.pageCount=data.pagesize;
                                    if(vue.pageNum==1){
                                        //第一次加载如果没有数据则显示无数据样式
                                        if(data.data.length==0){
                                            vue.hasData=false;
                                        }else {
                                            vue.list=data.data;
                                            vue.hasData=true;
                                            Vue.nextTick(function () {
                                                $(window).scrollTop(0);
                                            });
                                        };
                                    }else {
                                        for(var i=0;i<data.data.length;i++){
                                            vue.list.push(data.data[i])
                                        };
                                    };
                                    //判断是否到了最后一页
                                    vue.lastPage=vue.pageCount>data.data.length?true:false;
                                    vue.pageCount=data.pagesize;

                                    vue.pageNum=vue.pageNum+1;
                                    if(vue.lastPage){
                                        $(".lastext").show();
                                        $(".btmRefreshing").hide();
                                    }
                                })
                                if (cb) cb(data);
                            }
                        })
                    }
                },
                reset:function(){
                    vue.pageNum=1;
                    vue.lastPage=false;
                    //vue.hasData=true;
                    $(".lastext").hide();
                    $(".btmRefreshing").show();
                }
            }
        });

    exports.upDown=function(obj){

        $.extend(true,newObj,obj);
        vue.reset();
        vue.pageNum=obj.ajax.data.pageIndex;
        //vue.list=[];
        $(".btmDIv").hide();
        // 第一屏加载
        vue.getList(true,function(data){
            $(".dropload-layer").attr("data-refreshing","0");
            Vue.nextTick(function () {
                if($(document).height()>$(window).height()){
                    $(".btmDIv").show();
                }else {
                    $(".btmDIv").hide();
                };
            });
        });
    };
    exports.vue=vue;
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
    //上拉加载更多
    upDown.down(function(fun){
        vue.getList(false,function(data){
            //在dom渲染完执行
            Vue.nextTick(function () {
                fun.reset(vue.lastPage); //可以在成功执行了滚动到底部的事件后重置，防止多次执行事件
            })
        })
    })
// });