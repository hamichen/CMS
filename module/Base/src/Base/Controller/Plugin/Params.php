<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2014/6/1
 * Time: ¤U¤È 4:27
 */

namespace Base\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\Params as ZendParams;

class Params extends ZendParams
{

    public function fromJson() {
        $body = $this->getController()->getRequest()->getContent();
        if (!empty($body)) {
            $json = json_decode($body, true);
            if (!empty($json)) {
                return $json;
            }
        }

        return false;
    }
} 