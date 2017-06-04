// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/home.css

// ##====请求jquery
// @require ../../lib/jquery
// ##====请求validForm
// @require ../../lib/validForm

require('../common/init');

//登录弹层
var _poplogin = require('../common/poplogin');
_poplogin.poplogin.init();

// 进度  
require('../common/progress_animate');

// 切换
require('./swiper');

//header 菜单
var menu = require('../common/show_menu');
menu.showMenu({
    'clickHide': false
});

// 网站地图
require('./sitemap');

//倒计时
var countdown = require('../common/countdown');
/*定期倒计时*/
$('.J_countdown').each(function(){
    var $this = $(this);
    countdown.countdown($this, function(){
        $this.height(0);
        setTimeout(function(){
            $this.remove();
        }, 300);
    }); 
});
window.onload = function(){
    var url = window.location.href;
    var ps = url.split("#");
    try{
        if(ps[1] != 1){
            url += "#1";
        }else{
            window.location = ps[0];
        }
    }catch(ex){
        url += "#1";
    }

    window.location.replace(url);

};
//下载app
require('../common/downapp');