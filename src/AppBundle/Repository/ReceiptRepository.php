<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ReceiptRepository extends EntityRepository
{
    public function count()
    {
        return $this->createQueryBuilder('r')
        ->select('count(r.id)')
        ->getQuery()
        ->getSingleScalarResult();
    }
}