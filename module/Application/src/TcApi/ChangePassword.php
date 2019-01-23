<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/1/23
 * Time: 上午 10:40
 */

namespace Application\TcApi;


class ChangePassword extends TcApi
{
    public function __construct($sm)
    {
        parent::__construct($sm);
        $this->method = "PATCH";
        $this->apiName = 'change-password';
    }
}