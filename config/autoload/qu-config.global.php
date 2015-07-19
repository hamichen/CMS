<?php

return array(
    'QuConfig'=>array(
        'QuElFinder'=>array(
            'QuRoots'=>array(
                'driver'        => 'LocalFileSystem',
                //Module
                //'path'  =>  dirname(dirname(dirname(__DIR__)))  . '/web/uploads/files',
                //Vendor
                'path'  =>  getcwd()  . '/public/uploads/files',
                'URL'           =>  '/uploads/files',
                'accessControl' => 'access',
                'attributes' => array(
                    array(
                        'read'   => false,
                        'write'  => false,
                        'locked' => true,
                        'hidden' => false
                    )
                )

            ),

            'elFinder'=>array(
                'url'=>'/quelfinder/connector',
                'lang'=>   'zh_TW',
                'height'=> '500',
                'width'=> '500',
            ),

            'BasePath'=>'/js/plugins/elfinder',

            'LoadCss'=>array(

                'css'=> array(

                    /*
                    'common',
                    'dialog',
                    'toolbar',
                    'navbar',
                    'statusbar',
                    'contextmenu',
                    'cwd',
                    'quicklook',
                    'commands',
                    'fonts',
                    */
                    'style',
                    'theme',
                    'elfinder.min',

                ),
            ),

            'LoadJs'=>array(

                //'elfinder.min',

                'jquery.elfinder',
                'elFinder',
                'elFinder.version',
                'elFinder.resources',
                'elFinder.options',
                'elFinder.history',
                'elFinder.command',


                'ui'=> array(
                    'overlay',
                    'workzone',
                    'navbar',
                    'dialog',
                    'tree',
                    'cwd',
                    'toolbar',
                    'button',
                    'uploadButton',
                    'viewbutton',
                    'searchbutton',
                    'sortbutton',
                    'panel',
                    'contextmenu',
                    'path',
                    'stat',
                    'places',
                ),

                'commands'=> array(
                    'open',
                    'reload',
                    'home',
                    'up',
                    'back',
                    'forward',
                    'getfile',
                    'quicklook',
                    'download',
                    'rm',
                    'duplicate',
                    'rename',
                    'mkdir',
                    'mkfile',
                    'upload',
                    'copy',
                    'cut',
                    'paste',
                    'edit',
                    'extract',
                    'archive',
                    'search',
                    'info',
                    'view',
                    'help',
                    'resize',
                    'sort',
                    'netmount'
                ),

                'i18n'=> array(
                    'zh_TW'
                ),
            ),
        ),

    ),
);