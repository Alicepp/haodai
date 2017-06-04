
// ##====请求echarts
// @require ../../lib/echarts
	pos = [];
	var option = {
	    title: {
	        text: '七日收益率（%）',
	        textStyle: {
	        	color: '#808697',
	        	fontWeight: 'normal',
	        	fontSize: '12'
	        },
	        left: '3%',
	        top: '4%'
	    },
	    tooltip : {
	        trigger: 'item',
	        formatter: '{c}',
	        backgroundColor: '#ff6356',
	        borderWidth: 0,
	        borderColor: '#ff6356',
	        extraCssText: 'z-index:99;',
	        alwaysShowContent: true,
	        //position: 'top',
	        position: function (point, params, dom, pos) {

	        	window.pos.push({'x':pos.x, 'y': pos.y});
			      // 固定在顶部
			      /*console.log(pos);
			      return [point[0], '10%'];*/
			  },
	        axisPointer: {
	        	type: 'line',
	        	lineStyle: {
	        		color: '#fefefe',
	        		opacity: 0
	        	}
	        }
	    },
	    legend: {
	        data:['年化收益率'],
	        right: '4%',
	        bottom: '2%',
	        selectedMode: false,
	        itemWidth: 50,
	        itemHeight: 10,
	        textStyle: {
	        	color: '#8e909b',
	        	fontSize: '11'
	        }
	    },
	    grid: {	    	
	        left: '3%',
	        right: '5%',
	        top: '15%',
	        bottom: '14%',
	        containLabel: true
	    },
	    xAxis : [
	        {
	            type : 'category',
	            boundaryGap : false,
	            splitLine: {
	            	show: true,
	            	lineStyle: {
	            		color: '#efefef'
	            	}
	            },
	            axisLabel: {
            		textStyle: {
		            	color: '#b7b9c5'
		            }
	            },
	            axisLine: {
		            lineStyle: {
		            	color: '#ccc'
		            }
		        },

	            data : $('#incomeEcharts').data('date').split(',')
	        }
	    ],
	    yAxis : [
	        {
	            type : 'value',
	            splitLine: {
	            	show: true,
	            	lineStyle: {
	            		color: '#efefef'
	            	}
	            },
	            axisLabel: {
            		textStyle: {
		            	color: '#b7b9c5'
		            }
	            },
	            axisLine: {
		            lineStyle: {
		            	color: '#ccc'
		            }
		        }
	        }
	    ],
	    series : [
	        {
	            name:'年化收益率',
	            type:'line',
	            symbolSize: 6,
	            itemStyle: {
	            	/*normal:  {label : {show: true}}},*/
	            	normal: {
            			borderColor: '#ff6159'
	            	},
	            	emphasis: {
	            		color: '#ff6159',
	            		borderWidth: 1,
	            		borderColor: '#fff'
	            	}
	            },
	            lineStyle: {
	            	normal: {
            			color: '#ff6159'
	            	}
	            },
	            areaStyle: {
	                normal: {
	                    color: 'rgba(255, 220, 201, 0.5)'
	                }
	            },

	            markPoint: {
	            	label: {
	            		normal: {
	            			show: true
	            		}
	            	},
	                data: [
	                    {name: '', value: 2},
	                    {name: '', value: 3.1},
	                    {name: '', value: 0.5}
	                ]
	            },
	            data: $('#incomeEcharts').data('rate').split(',')
	        }
	    ]
	};

	var myChart = echarts.init(document.getElementById('incomeEcharts'));
	myChart.setOption(option);

	for(var i=0; i < 7; i++){
		myChart.dispatchAction({type: 'showTip', seriesIndex: i, dataIndex: i});
	};
	/**/
	var newOpt = {
		tooltip : {
	        trigger: 'axis',
	        formatter: '{c} <span style="display:block;position:absolute;left:50%;bottom:-9px;margin-left:-6px;width:10px;line-height:18px;font-size:14px;color:#ff6356;vertical-align:top;text-align:center;">◆</span>',
	        //position: 'top',
	        position: function (point, params, dom) {
	        	var h = $(dom).outerHeight();
	        	var w = $(dom).outerWidth();
	        	var posIndex = window.pos[params[0].dataIndex];
	        	return [posIndex.x - w/2, posIndex.y - h -13];
		  	}
	    }
	};

	myChart.setOption(newOpt);
