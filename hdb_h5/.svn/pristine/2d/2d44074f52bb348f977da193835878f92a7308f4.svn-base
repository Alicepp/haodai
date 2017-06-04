<?php
/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/31
 * Time: 18:00
 */

function all_config($type = array()) {
  switch ($type) {
    case 'bid';
      return $config = [
        'beginnerList' => '新手专区',
        'regularList' => '定期日息宝',
        'eliteSelectedList' => '精选项目',
        'bankTreasureList' => '银行宝',
        'bestBidList' => '优选项目',
        //特殊编号9
        'currentRxbList' => '活期日息宝',
        'planInfoList' => '理财计划',
        'serialNum' => '其他',
      ];
      break;

    case 'home';//首页标类型
      return $config = [
        'rookieBid' => '新手标',
        'rxb' => '定期日息宝',
        'rxbSubject' => '倒计时标',
        'hqrxb' => '活期日息宝',
        'jxlc' => '精选项目',
        'TopBidJxlc' => '精选项目',//置顶
        'jxlcSubject' => '精选项目',//倒计时标
        'TopBidRxb' => '定期日息宝',//置顶
        'rxbSubject' => '日息宝 ',//倒计时标
      ];
      break;

    case 'home_status';//标状态
      return $config = ['可以抢', '已抢光', '升级中'];
      break;

    case 'refundWay';//还款方式
      return $config = ['按月等额本息', '一次性还本息', '按月付息,到期还本', '每日返息,到期还本'];
      break;

    case 'bidType';//标类型
      return $config = ['日息宝', '精选项目', '银行宝', '全部', '新手标', 10 => '新手标'];
      break;

    case 'income_type';//活期日息宝
      return $config = ['hqrxb' => '七日预期年化', 'currentRxbList' => '七日预期年化'
      ];
      break;

    case 'qualityRating';//信用等级
      return $config = ['其他', 'AA', 'A', 'B', 'C', 'D', 'E', 'HR', 'AAA'];
      break;

    case 'maxEdu';//学历
      return $config = [1 => '高中或以下', 2 => '大专', 3 => '本科', 4 => '研究生或以上'];
      break;

    case 'marriage';//婚姻状态
      return $config = ['已婚', '未婚', '离异', '丧偶'];
      break;

    case 'audit_log';//审核记录
      return $config = [1=>'身份证',2=>'工作',3=>'信用报告',4=>'收入',5=>'房产',6=>'购车',7=>'结婚',8=>'学历',9=>'技术职称',10=>'手机实名',
        11=>'居住地',12=>'附加证明',21=> '营业执照',22=>'经营场所证明',23=>'个人工作证明',24=>'其它工作证明'];
      break;

    case 'monthIncome';//收入
      return $config = [0, '1000元及以下', '1001-2000元', '2001-5000元', '5001-10000元', '10001-20000元', '20001-50000元', '50000元以上'];
      break;

    case 'globalstatus';
      return $config = [
        '14' => '待发布',
        '16' => '发布中',
        '19' => '还款中', '23' => '还款中', '24' => '还款中', '32' => '还款中', '34' => '还款中', '37' => '还款中', '38' => '还款中',
        '18' => '已满标', '20' => '已满标', '22' => '已满标',
        '29' => '已结清', '33' => '已结清', '35' => '已结清', '36' => '已结清', '40' => '已结清',
        '17'=>'流标'
      ];
      break;

    case 'bidstatus';
      return $config = [1 => '待发布', 2 => '发布中', 3 => '还款中', 4 => '已满标', 5 => '已结清'];

    case 'vehicle_info';//车辆信息
      return $config = ['carBrandNo' => '车辆品牌型号', 'carNo' => '车牌号', 'licenseDate' => '初始登记日期', 'carColour' => '车辆颜色', 'runKm' => '行驶公里数', 'agentEvaluate' => '车辆评估价'];

    default:
      return array();
  }
}

function work_array_key_exists($key, $type) {

  switch ($type) {

    case 'income_type':
      $value = '预期年化';
      break;

    default:
      if (array_key_exists($key, all_config($type))) {
        $value = all_config($type)[$key];
      } else {
        $value = null;
      }
      break;
  }

  return $value;

}

/*
 *处理接口返回的数据
 * */
function update_array_value(&$data, array $appoint_key = null) {
  if (is_array($data)) {
    if (!empty($appoint_key)) {
      foreach ($appoint_key as $value) {
        switch ($value) {
          case 'tradeDate':
            if (array_key_exists($value, $data) && is_numeric($data[$value])) {
              $data[$value] = strlen($data[$value]) == 13 ? $data[$value] / 1000 : $data[$value];
              $data[$value] = date('Y-m-d H:i', $data[$value]);
            }
            break;

          case 'createdate':
          case 'createDate':
          case 'interestdate':
            if (array_key_exists($value, $data) && is_numeric($data[$value])) {
              $data[$value] = strlen($data[$value]) == 13 ? $data[$value] / 1000 : $data[$value];
              $data[$value] = date('Y-m-d', $data[$value]);
            }
            break;

          case 'globalStatus':
            if (array_key_exists($value, $data)) {
              $data['status_title'] = work_array_key_exists($data[$value], 'globalstatus');
              $data['bidStatus'] = array_search($data['status_title'], all_config('bidstatus'));
              $data['bidStatus'] || $data['bidStatus'] = 0;
            }
            break;

          case 'approvedPeriod':
            if (array_key_exists($value, $data)) {
              $data['approvedPeriod'] .= $data['approvedPeriodUnit'] == 2 ? '个月' : '天';
            }
            break;

          case 'publishDate':
            if (array_key_exists($value, $data)) {
              $data['publishDate'] = date('Y-m-d H:i:s', $data['publishDate']);
            }
            break;

          case 'amount'://格式化金额
          case 'balance':
          case 'outlay':
          case 'bidAmount':
            if (array_key_exists($value, $data)) {
              $data[$value] = formatMoney($data[$value]);
            }
            break;

          case 'resourceAmount':
          case 'limitAmount':
          case 'yesterdaygains':
            if (array_key_exists($value, $data)) {
              $data[$value] /= 100;
            }
            break;

          case 'accountRemin'://活期日息宝金额除以100
          case 'allinterest'://累计收益
          case 'allamount'://日息宝总余额
          case 'remainnum'://当前剩余可购额度
            if (array_key_exists($value, $data)) {
              $data[$value] /= 100;
            }
            break;
        }
      }
    }

    foreach ($data as $key => &$value) {
      if (is_array($value)) {
        update_array_value($value, $appoint_key);
      }
    }
  }

  return $data;
}