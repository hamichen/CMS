<?php
return array(
    'acl' => array(
        'roles' => array(
            'guest' => null,
            'teacher' => 'guest',
            'admin' => 'teacher',

        ),
        'resources' => array(

            'allow' => array(
                'application' => array(
                    'guest' => 'all'
                ),
                'user:sign' => array(
                    'guest' => 'all'
                ),
                'admin' => array(
                    'admin' => 'all'
                ),
                'cms' => array(
                    'admin' => 'all'
                ),
                'qu-el-finder' => array(
                    'admin' => 'all'
                )

            ),
// 						'deny' => array(
// 								'Admin:Code'  => array(
// 									'member' => 'all'
// 								),
// 						),						
        )
    )
);
