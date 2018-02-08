<?php
return [
    'acl' => [
        'roles' => [
            'guest' => null,
            '註冊會員' => 'guest',
            '一般使用者' => 'guest',
            '單位管理者' => '一般使用者',
            '系統管理者' => '單位管理者',

        ],
        'resources' => [

            'allow' => [
                'application' => [
                    'guest' => 'all'
                ],

                'tck-image-resizer' => [
                    '一般使用者' => 'all'
                ],
                'user' => [
                    '一般使用者' => 'all'
                ],
                'user:sign' => [
                    'guest' => 'all'
                ],

                'admin' =>[
                    '系統管理者' => 'all'
                ],

                'cms' =>[
                    '系統管理者' => 'all'
                ],

                'qu-el-finder' => [
                    '一般使用者' => 'all'
                ]

            ],
// 						'deny' => [
// 								'Admin:Code'  => [
// 									'member' => 'all'
// 								],
// 						],
        ],

    ]
];
