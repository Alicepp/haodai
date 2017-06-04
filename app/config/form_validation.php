<?php

$config = [
  'account/mobile' => [//手机号
    [
      'field' => 'mobilePhone',
      'label' => '手机号',
      'rules' => 'trim|required|numeric|min_length[11]|max_length[11]',
    ],
  ],
  'account/login' => [//首页登录
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
  'account/reset' => [//找回密码
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
];
