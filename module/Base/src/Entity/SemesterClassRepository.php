<?php
namespace Base\Entity;

use Doctrine\ORM\EntityRepository;

class SemesterClassRepository extends EntityRepository
{

    public function getClassArray($semester_id)
    {
        $res = $this->_em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\SemesterClass', 'u')
            ->leftJoin('u.semester','semester')
            ->where('semester.id=:semester_id')
            ->setParameter('semester_id', $semester_id)
            ->orderBy('u.grade')
            ->addOrderBy('u.class_no')
            ->getQuery()
            ->getArrayResult();
        $classArr = [];
        foreach ($res as $row) {
            $val = $row['grade'].'年'.$row['class_name'].'班';
            if ($row['tutor'])
                $val .= "(".$row['tutor'].")";
            $grade = $row['grade'].' 年級';

            $classArr[$grade][$row['id']] = $val;
        }

        $arr = [];
        foreach ($classArr as $id => $val) {
            $temp = array();
            $temp['label'] = $id;
            $temp['options'] = $val;
            $arr[] = $temp;
        }

        return $arr;
    }

}