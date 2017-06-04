<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| 菜单设定
*/
$firstLevelMenu = array(
	array(
		"id"=>"793E0D7FDCFB41AABA0302AF8DA23488",
		"menuHref"=>"/",
		"menuName"=>"首页",
	),
	array(
		"id"=>"A69E2ABEA80E4ECBB9B81B90AC7C8074",
		"menuHref"=>"/transaction/bank/bankcard",
		"menuName"=>"交易查询",
	),
	array(
		"id"=>"8729EC565100491F9ACCD01BB9B3943D",
		"menuHref"=>"/account/accountManage",
		"menuName"=>"账户管理"
	),
	array(
		"id"=>"DA23A95BF83B49D5AF3AB066038408FE",
		"menuHref"=>"/settlement/settlementManage",
		"menuName"=>"结算管理"
	),
    array(
		"id"=>"AF2F9BC1384540B492F4DA61438BCE9D",
		"menuHref"=>"/payForOthers/upload/uploadHome",
		"menuName"=>"代付出款"
	),
	array(
		"id"=>"53BD7DA898DB4CF598CC8481BF29EB05",
		"menuHref"=>"/systemAdmin/operator",
		"menuName"=>"系统管理"
	),
	array(
		"id"=>"1AE83004826E47049C625D997526CAD1",
		"menuHref"=>"/merchant/tenantCenter",
		"menuName"=>"商户中心"
	)
);

$secondLevelMenu = array(
		//交易查询的二级菜单，根据id匹配（目前其他一级菜单的二级菜单不用调接口）
		'A69E2ABEA80E4ECBB9B81B90AC7C8074'=>array(
			array(
                "id"=>"145DD40A5927470DB61D5C4FDFB53468",
                "menuHref"=>"/transaction/bank/bankcard",
                "menuName"=>"银行卡收单"
			),
			array(
                "id"=>"296582F5FB654EF5A3CDCB3099992851",
                "menuHref"=>"/transaction/internetPay/internetPayList",
                "menuName"=>"互联网支付"
			),
			array(
                "id"=>"2AD8BD43D184448A80E16945ADC378D4",
                "menuHref"=>"/transaction/credit/creditCardPay",
                "menuName"=>"信用卡还款"
			),
			array(
                "id"=>"729A7CF7937E4B188AB59F86C4517A18",
                "menuHref"=>"/transaction/payOther/payForOther",
                "menuName"=>"代付"
			),
			array(
                "id"=>"F851A33FFD8D44FBA980062668EB9DCF",
                "menuHref"=>"/transaction/recharge/accountRecharge",
                "menuName"=>"账户充值"
			)
		),
);
