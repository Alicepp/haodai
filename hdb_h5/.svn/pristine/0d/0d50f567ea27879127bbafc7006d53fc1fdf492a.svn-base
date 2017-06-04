exports.poplogin = {
    init: function(){  //关闭弹层的回调
        this.$popWrap = $('.J_popLogin');
        this.winH = $(window).height();

        this.bindEvents();
        this.verifyForm();
    },
    bindEvents: function(){
        var _this = this;
        //弹出
        $('.J_login').click(function(){
            _this.$popBtn = $(this);
            if(!$(this).data('islogin')){ //未登录
                _this.openPop();
                return false;
            }
        });
        //关闭弹层
        this.$popWrap.on('click', '.J_closeLogin', function(){
            _this.closePop();
            return false;    
        });

        /*动画结束 webkitTransitionEnd*/
        $('body').on('animationend webkitAnimationEnd', '.slideInUp', function(){
            $(this).removeClass('animated slideInUp');
        });
        $('body').on('animationend webkitAnimationEnd', '.slideOutDown', function(){
            $(this).removeClass('animated slideOutDown').hide();
            _this.hidePop();
        });

        $(window).on('resize', function(){
            var newH = $(window).height();
            if((newH > _this.winH)){
                _this.winH = newH;
                _this.$popWrap.animate({'height': $('body')[0].clientHeight}, 20);
                //_this.$popWrap.height($('body')[0].clientHeight);
            }
        }); 
    },
    verifyForm: function(){ //验证登录
        var _this = this;
        var verifyForm = require('../common/verifylogin').verifyLogin;
        verifyForm(true, function(){
            var url = _this.$popBtn.attr('href').replace(/%2F/g, '/');
            if(url == '/login/index'){
                href = '/';
            }else if(url.indexOf('login/index') > 0){
                href = url.replace(/\/login\/index\?hdburl=/, '');
            }else {
                href = url;
            }
            if(href.indexOf('://') < 0){
                window.location.href = href;
            }
        });

    },
    openPop: function(){ //弹出
        var _this = this;
        _this.$popWrap.css({'position':'absolute', 'left':0, 'top':0, 'bottom':0, 'z-index':10000, 'width':'100%', 'height':'100%'})
        $('html,body').addClass('hideScroll');
        setTimeout(function(){
            _this.$popWrap.show().addClass('animated slideInUp');
            _this.$popWrap.height($('body')[0].clientHeight);
        }, 50);
    },
    hidePop: function(){  //隐藏弹层
        this.$popWrap.hide();
    },
    closePop: function(){  //关闭弹层
        this.$popWrap.addClass('animated slideOutDown');
        $('html,body').removeClass('hideScroll');
        this.$popWrap.find('.customForm').remove();
        this.$popWrap.find('.jsPassWord, .jsUserPhone').val('');
        /*图形验证码回初始状态*/
        $graphCode = $('.loginForm .J_graphCodeMod');
        $graphCode.hide();
        $graphCode.find('.J_imgcodeIpt').attr('name', '').attr('ignore', 'ignore');
    }
}