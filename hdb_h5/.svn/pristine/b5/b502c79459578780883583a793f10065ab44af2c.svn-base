    var headerTop,refreshing=false,pullSetDivTop=[],pullSetDiv="pullSetDivTop";
    var mo=function(e){e.preventDefault();};
    function stop(){
        document.body.style.overflow='hidden';
        document.addEventListener("touchmove",mo,false);//禁止页面滑动
    }
    function move(){
        document.body.style.overflow='';//出现滚动条
        document.removeEventListener("touchmove",mo,false);
    }
    if($("header").length>0){
        setTimeout(function(){
            headerTop=$("header").offset().top||0;
        },500)
    };
    $("."+pullSetDiv).each(function(index,data){
        pullSetDivTop[index]=$(this).offset().top;
    });
    +function ($) {
        "use strict";

        $.support = (function () {
            var support = {
                touch: !!(('ontouchstart' in window) || window.DocumentTouch && document instanceof window.DocumentTouch)
            };
            return support;
        })();

        $.touchEvents = {
            start: $.support.touch ? 'touchstart' : 'mousedown',
            move: $.support.touch ? 'touchmove' : 'mousemove',
            end: $.support.touch ? 'touchend' : 'mouseup'
        };

        $.getTouchPosition = function (e) {
            e = e.originalEvent || e;
            if (e.type === 'touchstart' || e.type === 'touchmove' || e.type === 'touchend') {
                return {
                    x: e.targetTouches[0].pageX,
                    y: e.targetTouches[0].pageY
                };
            } else {
                return {
                    x: e.pageX,
                    y: e.pageY
                };
            }
        };
    }($);
    +function ($) {
        "use strict";

        var PTR = function (el) {
            this.dpi = Number($("html").attr("data-dpr")) / 2 || 1;
            this.container = $(el);
            this.distance = this.dpi == 0.5 ? 60 : (this.dpi == 1 ? 110 : 160);//回弹的高度
            this.arrowBgH = 53;//每一帧图片的高度
            this.steps = 3;//每次移动多少像素变化一针
            this.attachEvents();
        };

        PTR.prototype.touchStart = function (e) {
            if (this.container.hasClass("refreshing")) return;
            var p = $.getTouchPosition(e);
            this.start = p;
            this.diffX = this.diffY = 0;
        };

        PTR.prototype.touchMove = function (e) {
            var _this=this;
            if (this.container.hasClass("refreshing")) {
                return;
            };
            if (!this.start) return false;
            if (this.container.scrollTop() > 0) return;
            var p = $.getTouchPosition(e), arrowPosition;
            this.diffX = p.x - this.start.x;
            this.diffY = p.y - this.start.y;
            if (this.diffY < 0){
                return;
            }
            this.container.addClass("touching");
            e.preventDefault();
            e.stopPropagation();
            this.diffY = Math.pow(this.diffY, 0.8);
            this.statusArea.css("height", this.diffY);
            arrowPosition = this.arrowBgH * parseInt((this.diffY - this.arrowBgH * this.dpi) / this.steps);
            if (this.diffY >= this.arrowBgH * this.dpi && arrowPosition <= this.arrowBgH * 22) {
                this.container.find(".arrow").css("backgroundPosition", "0 -" + arrowPosition + "px")
            }
            if (this.diffY < this.distance) {
                this.container.removeClass("pull-up").addClass("pull-down");
            } else {
                this.container.removeClass("pull-down").addClass("pull-up");
            }

            $("header").removeClass("animates").css({
                "top":headerTop+this.diffY
            });
            $("."+pullSetDiv).each(function(index,data){
                $(this).removeClass("animates").css({
                    "top":pullSetDivTop[index]+_this.diffY
                });
            });
            this.container.trigger("pull-move", this.diffY);
            return false;
        };
        PTR.prototype.touchEnd = function (e) {
            var _this=this;
            this.start = false;
            if (this.diffY <= 0 || this.container.hasClass("refreshing")) return;
            this.container.removeClass("touching");
            this.container.removeClass("pull-down pull-up");

            if (Math.abs(this.diffY) <= this.distance) {
                this.statusArea.css("height", 0);
                //当没有拉到允许刷新的位置的时候
                $("header").addClass("animates").css({
                    "top":headerTop
                });
                $("."+pullSetDiv).each(function(index,data){
                    $(this).addClass("animates").css({
                        "top":pullSetDivTop[index]
                    });
                });
                var _this = this;
                setTimeout(function () {
                    _this.container.find(".arrow").css("backgroundPosition", "0 0")
                }, 300)
            } else {
                stop();
                this.container.find(".arrow").css("backgroundPosition", "0 -" + this.arrowBgH * 22 + "px");
                this.statusArea.css("height", this.distance);
                $("header").addClass("animates").css({
                    "top":this.distance
                });
                $("."+pullSetDiv).each(function(index,data){
                    $(this).addClass("animates").css({
                        "top":pullSetDivTop[index]+_this.distance
                    });
                });
                this.container.addClass("refreshing");
                this.container.trigger("pull-to-refresh", [this.diffY]);
            };

            e.preventDefault();
            e.stopPropagation();
            return false;
        };

        PTR.prototype.attachEvents = function () {
            var el = this.container;
            el.addClass("dropload");
            var tpl = ['<div class="dropload-layer" data-refreshing="0">', '<div class="inner">', '<div class="arrow"></div>',
                '<div class="loader"></div>', '<div class="down">下拉刷新</div>',
                '<div class="up">释放刷新</div>', '<div class="refresh"><div class="q1"></div><div class="q2"></div><div class="q3"></div></div></div></div>'];
            this.statusArea = $(tpl.join('')).prependTo(el);
            el.on($.touchEvents.start, $.proxy(this.touchStart, this));
            el.on($.touchEvents.move, $.proxy(this.touchMove, this));
            el.on($.touchEvents.end, $.proxy(this.touchEnd, this));
        };

        var pullToRefresh = function (el) {
            new PTR(el);
        };
        var pullToRefreshDone = function (el) {
            move()
            $(el).removeClass("refreshing");
            $(el).find('.dropload-layer').css("height", 0);
            $("header").css({
                "top":headerTop
            });
            $(".dropload-layer").attr("data-refreshing","0");
            $("."+pullSetDiv).each(function(index,data){
                $(this).css({
                    "top":pullSetDivTop[index]
                });
            });
            setTimeout(function () {
                $(el).find(".arrow").css("backgroundPosition", "0 0");
            }, 300)

        };

        $.fn.pullToRefresh = function () {
            return this.each(function () {
                pullToRefresh(this);
            });
        };

        $.fn.pullToRefreshDone = function () {
            return this.each(function () {
                pullToRefreshDone(this);
            });
        };

    }($);
    +function ($){
        "use strict";

        var DTR=function(cb){
            var _this=this;
            $(".dropload-layer").attr("data-refreshing","0");
            this.adjustHeight=100;//定义滚动到距离页面底部多少像素开始执行方法
            this._scrollHeight=$(document).height();//获取文档的高度
            this._windowHeight=$(window).height();//获取可视窗口的高度
            this._scrollTop=$(window).scrollTop();//获取windeow滚动的高度
            this.init();
            $(window).scroll(function(){
                _this.resetHeight();
                _this.highPull(cb);
            });
        };
        //初始化页面
        DTR.prototype.init=function(){
            if($(".btmDIv").length==0){
                $("body").append('' +
                    '<div class="btmDIv">'+
                    '<div class="btmRefreshing"></div>'+
                    '<div class="lastext">到最后了</div>'+
                    '</div>'
                );
            };
        };
        DTR.prototype.reset=function(b){
            $(".dropload-layer").attr("data-refreshing","0");
            this.resetHeight();
            this.lastPage(b);
        };
        /*重置页面高度和屏幕可见高度*/
        DTR.prototype.resetHeight=function(){
            this._scrollHeight=$(document).height();
            this._windowHeight=$(window).height();
        };
        /*在加载数据后dom渲染完毕执行*/
        DTR.prototype.lastPage=function(b){
            if(b){
                $(".lastext").show();
                $(".btmRefreshing").hide();
            }else {
                $(".lastext").hide();
                $(".btmRefreshing").show();
            };
        };
        DTR.prototype.highPull=function(cb){
            this._scrollTop=$(window).scrollTop();
            if(this._scrollHeight-this.adjustHeight<this._scrollTop+this._windowHeight&&this._scrollHeight!=this._windowHeight){
                if($(".dropload-layer").attr("data-refreshing")=="0"){
                    $(".dropload-layer").attr("data-refreshing","1");
                    if(cb) cb(this);
                };
            };
        };
        var downToRefresh =function(cb){
            new DTR(cb);
        };
        $.fn.downToRefresh=function(cb){
            downToRefresh(cb);
        }
    }($);