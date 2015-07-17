<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2015/7/17
 * Time: 下午 08:54
 */

namespace Base\Controller\Plugin;


use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Download extends AbstractPlugin
{

    public function __invoke($file, $sourceName=null)
    {
        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($file, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($file));
        $headers = new \Zend\Http\Headers();
        if (!$sourceName)
            $sourceName = basename($file);

        $file_extension = strtolower(substr(strrchr($file,"."),1));
        switch ($file_extension) {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "odt": $ctype="application/vnd.oasis.opendocument.text"; break;
            case "doc": $ctype="application/msword"; break;
            case "docx": $ctype="application/vnd.openxmlformats-officedocument.wordprocessingml.document"; break;
            case "dotx": $ctype="application/vnd.openxmlformats-officedocument.wordprocessingml.template"; break;
            case "ods": $ctype="application/vnd.oasis.opendocument.spreadsheet"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "xlsx": $ctype="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; break;
            case "xltx": $ctype="application/vnd.openxmlformats-officedocument.spreadsheetml.template"; break;
            case "xlam": $ctype="application/vnd.ms-excel.addin.macroEnabled.12"; break;
            case "xlsb": $ctype="application/vnd.ms-excel.sheet.binary.macroEnabled.12"; break;
            case "odp": $ctype="application/vnd.oasis.opendocument.presentation"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "potx": $ctype="application/vnd.openxmlformats-officedocument.presentationml.template"; break;
            case "ppsx": $ctype="application/vnd.openxmlformats-officedocument.presentationml.slideshow"; break;
            case "pptx": $ctype="application/vnd.openxmlformats-officedocument.presentationml.presentation"; break;
            case "sldx": $ctype="application/vnd.openxmlformats-officedocument.presentationml.slide"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "mp3": $ctype="audio/mpeg3"; break;
            case "wav": $ctype="audio/wav"; break;
            case "mpeg":
            case "mpg": $ctype="video/mpeg"; break;
            case "avi": $ctype="video/avi"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default: $ctype="application/force-download";
        }

        $headers->addHeaders(array(
            'Content-Disposition' => 'attachment; filename="' . $sourceName .'"',
            'Content-Type' => $ctype,
            'Content-Length' => filesize($file),
            'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public'
        ));
        $response->setHeaders($headers);
        return $response;
    }
}