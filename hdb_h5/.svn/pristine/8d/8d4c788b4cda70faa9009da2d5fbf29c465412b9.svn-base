// ##====请求jquery
// @require ../../lib/jquery
	exports.init=function(){
		// 资金明细饼图
		exports.fundFn();
	}
	// 资金明细饼图
	exports.fundFn=function(){
		// ##====请求echarts
		// @require ../../lib/echarts
		var myChart=echarts.init(document.getElementById('myChart'));
		var minDeg=1.8;
		var _usableA=Number($('.jsUsableAmount').attr('usableA')), // 可用余额
			_frozenAmount=Number($('.jsFrozenAmount').attr('frozenAmount')), // 冻结金额
			_totalCollection=Number($('.jsTotalCollection').attr('totalCollection')), // 待收金额
			_accountRemin=Number($('.jsAccountRemin').attr('accountRemin')), // 日息宝金额
			_totalMount=_usableA+_frozenAmount+_totalCollection+_accountRemin;
		_usableA=_usableA===0?0:(_usableA*360)/_totalMount<minDeg?minDeg*_totalMount/360:_usableA;
		_frozenAmount=_frozenAmount===0?0:(_frozenAmount*360)/_totalMount<minDeg?minDeg*_totalMount/360:_frozenAmount;
		_totalCollection=_totalCollection===0?0:(_totalCollection*360)/_totalMount<minDeg?minDeg*_totalMount/360:_totalCollection;
		_accountRemin=_accountRemin===0?0:(_accountRemin*360)/_totalMount<minDeg?minDeg*_totalMount/360:_accountRemin;


		option = {
		    tooltip : {
		        trigger: 'item',
		        formatter: "{b} : {c} ({d}%)"
		    },
		    color:['#7ecef4','#f57f7e','#f6ca77','#bfb0ff'],
		    legend: {
		        orient : 'vertical',
				right:'0px',
		        y:'bottom',
				width:"100%",
		        data:['可用余额','冻结金额','待收金额','日息宝金额'],
				textStyle:{
					fontSize:10
				},
				align:'right'
		    },
		    calculable : true,
		    series : [
		        {
		            name:'',
		            type:'pie',
		            radius : ['20%', '75%'],
					stillShowZeroSum:true,
		            itemStyle : {
		                normal : {
		                    label : {
		                        show : false
		                    },
		                    labelLine : {
		                        show : false
		                    }
		                },
		                emphasis : {
		                    label : {
		                        show : true,
		                        position : 'center',
		                        textStyle : {
		                            fontSize : '3rem',
		                            fontWeight : 'bold'
		                        }
		                    }
		                }
		            },
		            data:[
		                {value:_usableA, name:'可用余额'},
		                {value:_frozenAmount, name:'冻结金额'},
		                {value:_totalCollection, name:'待收金额'},
		                {value:_accountRemin, name:'日息宝金额'}
		            ]
		        }
		    ]
		};
		myChart.setOption(option);
	}
