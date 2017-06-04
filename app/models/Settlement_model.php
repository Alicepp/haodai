<?php
/**
 * 结算管理
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settlement_model extends Base_model
{
    //币种设置
    public $currency=['CNY'=>'人民币'];

    protected function _init()
    {
        parent::_init();
    }

    /**
     * //获取结算信息(分页)
     *  杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/settle/show
     * @param int $page 页码
     * @param int $number 每页条数
     * @param string $currency 币种
     * @param string $startDate 开始时间
     * @param string $endDate 结束时间
     * @return array
     */
    public function getSettlementList($page = 1, $number = 10, $data = [])
    {
        $param = array_filter($data);
        $param['page'] = isUInt($page, 1) ? $page : 1;
        $param['number'] = isUInt($number, 1) ? $number : 10;

        isset($param['startDate']) && $param['startDate'] = date('Y-m-d', strtotime($param['startDate']));
        isset($param['endDate']) && $param['endDate'] = date('Y-m-d', strtotime($param['endDate']));

        $rst = $this->fetchPost('/payment/v1/moms/settle/show', $param);
        return $rst['result'];
    }

    /**
     * //获取账单类型权限枚举
     *  杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/bill/getBillEnum
     * @return array
     */
    public function getBillEnum()
    {
        $rst = $this->fetchPost('/payment/v1/moms/bill/getBillEnum');
        return $rst['result'];
    }


    /**
     * //获取下载对账单接口
     *  杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/bill/getBillDownUrl
     * @return string
     */
    public function getBillDownUrl($billDate, $productType)
    {
        $billDate && $param['billDate'] = date('Y-m-d', strtotime($billDate));
        $productType && $param['productType'] = $productType;

        $rst = $this->fetchPost('/payment/v1/moms/bill/getBillDownUrl', $param);
        return $rst['result'];
    }

}
