<?php
namespace Base\View\Helper;

use Doctrine\Orm\EntityManager;
use Zend\View\Helper\AbstractHelper;

/**
 * 系統頁面
 * Class SystemPage
 * @package Base\View\Helper
 */
class SystemPage extends AbstractHelper
{

    protected $em;

    public function __invoke($pageName)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\Page', 'u')
            ->where('u.kind=:kind')
            ->andWhere('u.title=:pageName')
            ->setParameter('kind', 'layout')
            ->setParameter('pageName', $pageName)
            ->getQuery()
            ->getOneOrNullResult();
        return $qb;
    }

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

}