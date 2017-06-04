// 请求swiper
// @require ../../lib/swiper

/*轮播图*/
var topSwiper = new Swiper ('.J_swiper', {
    autoplay : 5000,
    loop: true,
    lazyLoading: true,
    autoplayDisableOnInteraction: false,
    // 如果需要分页器
    pagination: '.swiper-pagination'
  });      

/*公告轮播*/
var noticeSwiper = new Swiper ('.J_swiperNotice', {
	direction: 'vertical',
    autoplay : 3000,
    loop: true
  });     