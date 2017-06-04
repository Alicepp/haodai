// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/home_testshare.css

    $("#testshare").click(function(event) {
        hdbShare({
            title: 'test', // 标题
            desc: 'test', //描述
            imgurl: 'http://www.haodaibao.com/images/guarantee-2.png', //分享出去的图片地址
            shareurl: 'http://qianbao.com/', //分享出去后点击的
            callback: function(isSuccess) {
                if (isSuccess) {
                    alert("分享成功");
                } else {
                    alert("分享失败");
                }
            }
        })
    });


    showShareBtn({
        title: 'test', // 标题
        desc: 'test', //描述
        imgurl: 'http://www.haodaibao.com/images/guarantee-2.png', //分享出去的图片地址
        shareurl: 'http://qianbao.com/', //分享出去后点击的);
        callback: function(state) {
            if (!!state) {
                alert("分享成功");
            }
        }
    })

    var wxinfo = {
        appname: "haodaibao",
        title: 'test', // 标题
        desc: 'test', //描述
        imgurl: 'http://www.haodaibao.com/images/guarantee-2.png', //分享出去的图片地址
        shareurl: 'http://qianbao.com/' //分享出去后点击的);
    };

    if (user_phone) {
        $("#testshare2").attr('href', hdbShare({
            title: 'test', // 标题
            desc: 'test', //描述
            imgurl: 'http://www.haodaibao.com/images/guarantee-2.png', //分享出去的图片地址
            shareurl: 'http://qianbao.com/' //分享出去后点击的);
        }));
    } else {
        $("#testshare2").attr('href', hdbLogin("/home/testshare?hdbfrom=app"));
    }

window.ajaxHost = location.href.substr(0, location.href.length - (location.pathname + location.search).length);

function fn_ShareToApp(shareObj) {
    var url = window.ajaxHost + "?needapp=share";
    if (shareObj) {
        url += "&title=" + shareObj.title;
        url += "&desc=" + shareObj.desc;
        url += "&imgurl=" + shareObj.imgurl;
        url += "&shareurl=" + shareObj.shareurl;
    }
    return url;
}