<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

/**
 * 菜单信息
 */
return [
  [
    'title' => '萌宠圈',
    'icon' => 'fa fa-fw fa-github-alt',
    'url_type' => 'module',
    'url_value' => 'pet/index/index',
    'url_target' => '_self',
    'online_hide' => 0,
    'sort' => 100,
    'child' => [
      [
        'title' => '商家管理',
        'icon' => 'fa fa-fw fa-users',
        'url_type' => 'module',
        'url_value' => 'pet/business/index',
        'url_target' => '_self',
        'online_hide' => 0,
        'sort' => 100,
        'child' => [
          [
            'title' => '商家列表',
            'icon' => 'fa fa-fw fa-th',
            'url_type' => 'module',
            'url_value' => 'pet/business/index',
            'url_target' => '_self',
            'online_hide' => 0,
            'sort' => 100,
            'child' => [
              [
                'title' => '新增',
                'icon' => '',
                'url_type' => 'module',
                'url_value' => 'pet/business/add',
                'url_target' => '_self',
                'online_hide' => 0,
                'sort' => 100,
              ],
            ],
          ],
        ],
      ],
    ],
  ],
];
