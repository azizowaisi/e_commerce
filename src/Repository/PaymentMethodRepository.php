<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\PaymentMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PaymentMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentMethod::class);
    }

    public function getPaymentMethod(Customer $customer, string $method, string $authorizedId)
    {
        $qb = $this->createQueryBuilder("pm");

        $qb->andWhere("pm.customer = :customer");
        $qb->setParameter("customer", $customer);

        $qb->andWhere("pm.method = :paymentMethod");
        $qb->setParameter("paymentMethod", $method);

        $qb->andWhere("pm.authorizedId = :authorizedId");
        $qb->setParameter("authorizedId", $authorizedId);

        $qb->setFirstResult(0);
        $qb->setMaxResults(1);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
}