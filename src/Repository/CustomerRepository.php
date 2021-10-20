<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function getCustomerByEmail(string $email)
    {
        $qb = $this->createQueryBuilder("customer");

        $qb->andWhere("customer.email = :email");
        $qb->setParameter("email", $email);

        $qb->setFirstResult(0);
        $qb->setMaxResults(1);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getCustomerByKey(string $customerKey)
    {
        $qb = $this->createQueryBuilder("customer");

        $qb->andWhere("customer.customerKey = :cusotmerKey");
        $qb->setParameter("cusotmerKey", $customerKey);

        $qb->setFirstResult(0);
        $qb->setMaxResults(1);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

}