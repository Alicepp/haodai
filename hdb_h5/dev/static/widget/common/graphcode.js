
	var _qb_ajax = require('../common/qb_ajax');
    var ajax = _qb_ajax.qbAjax;

	$('.J_graphCode').on('click', function(){
		var $graph = $(this);
		var url = $graph.data('url');
		ajax({
			type: 'get',
			url: url,
			data: {},
			success: function(json){
				$graph.attr('src', json.result.image);
			}
		});
	});