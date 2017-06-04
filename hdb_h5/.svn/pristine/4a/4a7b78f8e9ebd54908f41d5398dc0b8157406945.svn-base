var _qb_ajax = require('../common/qb_ajax');
var ajax = _qb_ajax.qbAjax;

$('.J_closeDown').click(function(){
	var $this = $(this);
	ajax({
		type: 'get',
		url: '/common/hidedownload',
		data: {},
		success: function(json){
			$this.parents('.J_downApp').remove();
		}
	});
});