	var _qb_ajax = require('../common/qb_ajax');
	var ajax = _qb_ajax.qbAjax;
	var _p = require("../common/pageLoading");
	var pageLoading=_p.pageLoading;
	var selectVue = new Vue({
		el: '.J_selectBank',
		data: {
			initial: true,
			bankName: '',
			bankCode: ''
		},
		methods: {
			selectBank: function(){
				if(!this.hasClick){ /*首次点击*/
					$.when( 
						bankList.getBankList() 
					).then(function(){
					 	bankList.showList();
					});
				}else{
					bankList.showList();
				}
				this.hasClick = true;
			}
		}

	});
	/* 银行列表 */
	var bankList = new Vue({
		    el: '.J_bankList',
		    data: {
		    	isIn: false,
		    	isOut: false,
		    	items: []
		    },
		    
		    methods: {
		    	getBankList: function(){
					var vm = this;
					return ajax({
						type: 'get',
						url: '/my/realname/getbankcardList',
						data: {},
						success: function(json){
							pageLoading(false);

							var list = json.data || [];
							vm.items = list;
						}
					});
				},

				showList: function(){
					$(this.$el).show();
					this.isIn = true;
					this.isOut = false;
				},

				hideList: function(){
					this.isIn = false;
					this.isOut = true;
					$('.J_mainView').show();
					$("html,body").stop().animate({scrollTop:0}, 100);
				},

				selected: function(item){
			    	selectVue.bankName = item.bankName;
			    	selectVue.bankCode = item.bankCode;
			    	selectVue.initial = false;

			    	this.hideList();
				}

		    }
		});