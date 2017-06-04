<?php

return [
  'plugin_path' => 'plugin/',
  'plugin_modules' => [
    '_begin' => [
      '*' => [//【目录/】控制器|* => [【目录/】文件 => 函数]
        '_common/Initial_Plugin' => 'begin',
        '_common/Limit_Plugin' => 'begin',
        'Check_Login' => 'begin',
        'Check_Realname' => 'begin',
        'Check_Getparameter'=>'begin',
        'Plugin_Output_Cache'=>'begin',
      ],
    ],
    '_end' => [
      '*' => []
    ]
  ]
];
