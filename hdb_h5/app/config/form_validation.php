<?php

$config = [
  'account/mobile'  => [ //手机号
    [
      'field' => 'mobilePhone',
      'label' => '手机号',
      'rules' => 'trim|required|numeric|min_length[11]|max_length[11]',
    ],
  ],
  'account/login'   => [ //首页登录
    [
      'field' => 'mobilePhone',
      'label' => '手机号',
      'rules' => 'trim|required|numeric|min_length[11]|max_length[11]',
    ],
    [
      'field' => 'passWord',
      'label' => '登录密码',
      'rules' => 'trim|required|min_length[6]|max_length[32]',
    ],
  ],
  'account/login/pwd' => [ //修改登录密码
    [
      'field' => 'passWord',
      'label' => '登录密码',
      'rules' => 'trim|required|min_length[6]|max_length[32]',
    ],
  ],
  'account/transaction/pwd' => [ //修改|设置 交易密码
    [
      'field' => 'passWord',
      'label' => '交易密码',
      'rules' => 'trim|required|min_length[6]|max_length[20]',
    ],
  ],
  'account/reset'   => [ //找回密码
    [
      'field' => 'mobilePhone',
      'label' => '手机号',
      'rules' => 'trim|required|numeric|min_length[11]|max_length[11]',
    ],
    [
      'field' => 'msgCode',
      'label' => '短信验证码',
      'rules' => 'trim|required|numeric|min_length[6]|max_length[6]',
    ],
    [
      'field' => 'password',
      'label' => '登录密码',
      'rules' => 'trim|required|min_length[6]|max_length[32]',
    ],
  ],
  'account/register'   => [ //注册
    [
      'field' => 'mobilePhone',
      'label' => '手机号',
      'rules' => 'trim|required|numeric|min_length[11]|max_length[11]',
    ],
    [
      'field' => 'msgCode',
      'label' => '短信验证码',
      'rules' => 'trim|required|numeric|min_length[6]|max_length[6]',
    ],
    [
      'field' => 'pwd',
      'label' => '注册密码',
      'rules' => 'trim|required|min_length[6]|max_length[32]',
    ],
  ],
  'bills/sendemail' => [ //帐单发送
    [
      'field' => 'emailTo',
      'label' => '邮件',
      'rules' => 'required|valid_email',
    ],
  ],
];
