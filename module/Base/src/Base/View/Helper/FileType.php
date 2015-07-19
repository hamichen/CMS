<?php


namespace Base\View\Helper;


use Zend\View\Helper\AbstractHelper;

class FileType extends AbstractHelper
{

    /**
     * 將檔案 type icon
     * @param string $type
     * @return string
     */
    public function __invoke($type)
    {
        $arr = [
            'jpg' => 'fa fa-file-image-o',
            'png' => 'fa fa-file-image-o',
            'bmp' => 'fa fa-file-image-o',
            'gif' => 'fa fa-file-image-o',
            'xls' => 'fa fa-file-excel-o',
            'xlsx' => 'fa fa-file-excel-o',
            'doc' => 'fa fa-file-word-o',
            'docx' => 'fa fa-file-word-o',
            'ppt' => 'fa fa-file-powerpoint-o',
            'pptx' => 'fa fa-file-powerpoint-o',
            'zip' => 'fa fa-file-archive-o',
            'gz' => 'fa fa-file-archive-o',
            'mp3' => 'fa fa-file-sound-o',
            'odd' => 'fa fa-file-sound-o',
            'midi' => 'fa fa-file-sound-o',
            'mp4' => 'fa fa-file-video-o',
            'txt' => 'fa fa-file-text-o',
            'pdf' => 'fa fa-file-pdf-o',
            'php' => 'fa fa-file-code-o',
        ];


        if (isset($arr[$type]))
            return $arr[$type];
        else
            return 'fa fa-file';

    }
} 