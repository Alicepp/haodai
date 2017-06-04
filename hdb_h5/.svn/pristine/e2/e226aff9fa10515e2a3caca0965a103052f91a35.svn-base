<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends qb_Controller {

  public function index() {//该接口还没设计好
    $data['title'] = '首页';
    $this->load->model('v2/Home_model', 'home');

    $data['home'] = $this->home->index();

    $result = array('top' => array(), 'rookieBid' => array(), 'hqrxb' => array(), 'bottom' => array());
    $result['top']['count_down'] = [];
    $result['top']['top'] = [];
    if (API_HDB_SUCCESS == $data['home']['status']) {
      foreach ($data['home']['result'] as $key => &$value) {
        if (is_array($value) && array_key_exists('type', $value)) {
          $value['type_title'] = work_array_key_exists($value['type'], 'home');
          switch ($value['type']) {
            case 'rxbSubject'://定期日息宝倒计时标
            case 'jxlcSubject'://精选项目倒计时标
              array_key_exists('selltime', $value) && $result['top']['count_down'][] = $value;//头部--倒计时
              break;

            case 'TopBidRxb'://定期日息宝置顶
            case 'TopBidJxlc'://精选项目置顶
              $result['top']['top'][] = $value;//头部---置顶
              break;

            case 'rookieBid':
              $result['rookieBid'][] = $value;//新手标
              break;

            case 'hqrxb':
              $result['hqrxb'][] = $value;//活期日息宝
              break;

            case 'rxb':
            case 'jxlc':
              $result['bottom'][] = $value;//底部
              break;

          }
        } else {
          unset($value);
        }
      }
    }

    unset($data['home']);
    $data['notice'] = $this->Qbao_model->getSystemMessageList(1, 3);//首页取前三条
    $data['isNew'] = _Super_checkMemberIsNew();
    $data['nav'] = $this->Qbao_model->getListHome();
    $data['banner'] = $this->Qbao_model->getListBanner();

    $this->smarty->assign('result', $result);
    $this->smarty->view('home', $data);
  }

  // 公司介绍
  public function safe() {
    $ret['title'] = '安全保障';
    $this->smarty->view('safe', $ret);
  }

  public function service() {
    $data['title'] = '客户服务';
    $this->smarty->view('service', $data);
  }

  public function about() {
    $data['title'] = '关于我们';
    $this->smarty->view('about', $data);
  }

  public function more() {
    $data['title'] = '更多';
    $this->smarty->view('more', $data);
  }

  // 了解我们
  public function huodongUs() {
    echo "<title>请稍后</title><meta http-equiv=refresh content='0; url=http://huodong.haodaibao.com/20161117'>";
  }

}
