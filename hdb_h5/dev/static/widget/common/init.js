//解决点击300ms延迟的问题
var _fastclick = require("../common/fastclick");
_fastclick.ft("fastclick");
//解决iso active 不起作用
document.body.addEventListener('touchstart', function () {},false);