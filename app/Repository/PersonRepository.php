<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\ORM\EntityRepository;

class PersonRepository extends EntityRepository
{
    public function all()
    {
        return $this->createQueryBuilder()
            ->select('u')
            ->from(Person::class, 'u')
            ->getQuery()->getResult();
    }
}