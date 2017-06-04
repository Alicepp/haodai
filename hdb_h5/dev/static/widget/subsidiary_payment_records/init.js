/**
 *  Class: init
 *  Author: lvpeipei
 *  Date: 2016/10/17.
 *  Description:回款记录
 */

// ##====请求css
// @require ../../css/common/common.css
// @require ../../css/subsidiary_payment_records.css
// ##====请求swiper
// @require ../../lib/swiper
    /*轮播图*/
    var topSwiper = new Swiper ('.payment-tab', {
        autoplay : false,
        loop: true,
        lazyLoading: true,
        autoplayDisableOnInteraction: false,
        // 如果需要分页器
        pagination: '.nav-payment'
    });