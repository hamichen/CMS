<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2016 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2016/9/11
 * Time: 下午 11:10
 */

namespace Base\View\Helper;


use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\View\Exception;


class LayoutBasePath extends AbstractHelper
{
    /**
     * layout Path
     *
     * @var string
     */
    protected $path;

    /**
     * return layout path
     *
     * If none available, returns null.
     * @throws Exception\RuntimeException
     * @return mixed|null
     */
    public function __invoke()
    {

        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

}