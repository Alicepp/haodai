<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends qb_Controller {

  public $Intro_title = null;
  public $Intro_list = null;

  public function _init() {
    $this->Intro_title = array('项目介绍', '注册认证', '充值提现', '财务管理', '业务知识', '常见问题');
    $this->Intro_list = array(
      array('项目介绍', '借款项目类型', '我们的承诺'),
      array('账户注册', '实名认证', '账户安全'),
      array('关于充值', '绑定银行卡', '关于提现'),
      array('关于出借', '如何查看出借情况', '如何收回出借资金'),
      array('什么是预期年化', '什么是等额本息还款法', '什么是每日还息,到期还本法', '如何减小出借风险', '信用等级是如何分的', '利息何时起算', '借款人未按时还款怎么办', '借款人提前还款,收益怎么算 '),
      array('平台模式合法化', '借款人都是做什么的', '借款人会不会负担不了每月的还款', '保障措施', '网站服务协议')
    );
  }

  // 帮助中心
  public function index() {
    $ret['title'] = '帮助说明';
    $this->smarty->view('help/help_index', $ret);
  }

  public function intro() {
    $ret['title'] = '项目介绍';
    $this->smarty->view('help/intro', $ret);
  }

  // 列表
  public function title_list() {
    $type = !empty($this->_get('type')) ? $this->_get('type') : 0;
    $data['title'] = $this->Intro_title[$type];
    $data['title_id'] = $type;
    $data['list'] = $this->Intro_list[$type];
    $this->smarty->view('help/list_' . $type, $data);
  }

  // 内容
  public function content() {
    $list = !empty($this->_get('index')) ? $this->_get('index') : 0;
    $type = !empty($this->_get('type')) ? $this->_get('type') : 0;
    $data['title'] = $this->Intro_list[$type][$list];
    $url = 'help/content_' . $type . '_' . $list;
    $this->smarty->view('help/content_' . $type . '_' . $list, $data);
  }

  public function detail() {
    $data['title'] = '项目介绍';
    $this->smarty->view('help/detail', $data);
  }

  public function rechargerule() {
    $data['title'] = '充值提现规则';
    $data['banklist'] = _Super_getSupportBankList();
    $this->smarty->view('help/rechargerule', $data);
  }

  public function test() {
    dump($this->Intro_title);
    dump($this->Intro_list);
  }

}
