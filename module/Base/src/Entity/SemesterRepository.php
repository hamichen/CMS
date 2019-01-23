<?php
namespace Base\Entity;

use Doctrine\ORM\EntityRepository;

class SemesterRepository extends EntityRepository
{

    public function getSemesterArray()
    {
        $res = $this->_em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\Semester', 'u')
            ->orderBy('u.year', 'DESC')
            ->addOrderBy('u.semester', 'DESC')
            ->getQuery()
            ->getArrayResult();
        $arr = [];
        foreach ($res as $row)
            $arr[$row['id']] = $row['year'].'學年第'.$row['semester'].'學期';

        return $arr;
    }

}