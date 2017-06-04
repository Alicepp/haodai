/*
* 传入参数为对象，类似{url:"/",success:function(data){}}
* 使用: var ajax=require('../common/qb_ajax').qbAjax
* ajax({
*   type:"post",
*   url:"/",
*   data:{},
*   success:function(){}
* })
* 依赖jquery，请确保jquery先行加载
* 如果不传入参数以下面ajaxObj的默认定义为主，如果ajaxObj中也没有的参数以jquery中默认定义的为主
*/
// define(function(require, exports) {
    var jsLog=require("../common/js_log").jsLog,
        pageLoading=require("../common/pageLoading").pageLoading,
        judgeenv=require("../common/judgeenv").judgeEnv;

    //默认配置
    exports.ajaxObj={
        type: "GET",//GET/POST
        url: "/",
        async:true,//true:为异步；false为异步
        data:{},
        dataType: "json",//xml,html,script,json,jsonp
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        processData:true,//false: {foo:["bar1", "bar2"]};ture:'&foo=bar1&foo=bar2'
        beforeSend:function(){},
        success:function(){},
        complete:function(data){
            var _this=this;
            //判断当前环境,如果是测试环境和开发环境则执行
            if(judgeenv()){
                jsLog(
                    "来源地址:"+window.location.href+" ["+new Date().toLocaleString()+"]"
                    +"\n接口地址:"+_this.url
                    +"\n请求方式:"+_this.type
                    +"\n提交参数:"+JSON.stringify(_this.data)
                    +"\n接口返回:"+JSON.stringify(data.responseJSON)
                    +"\n是否异步:"+_this.async
                    +"\n是否序列化:"+_this.processData
                );
            }
            pageLoading(false);
        }
    };
    exports.qbAjax=function(obj){
        //设置加载超时时间为10秒
        if(obj.needLoading!==false){
            pageLoading(true);
        }
        //setTimeout(function(){
        //    pageLoading(false);
        //},60000)
        obj=typeof(obj)=="object"? $.extend({},exports.ajaxObj,obj):(function(){console.warn("Check up ajax Object whether correct?"); return false})();
        if(!obj) return;
        $.ajax(obj)
    }
// })