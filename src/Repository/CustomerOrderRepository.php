<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\CustomerOrder;
use App\Entity\PaymentMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerOrder::class);
    }

    public function getOrder(Customer $customer, PaymentMethod $paymentMethod)
    {
        $qb = $this->createQueryBuilder("customer_order");

        $qb->andWhere("customer_order.customer = :customer");
        $qb->setParameter("customer", $customer);

        $qb->andWhere("customer_order.paymentMethod = :payment");
        $qb->setParameter("payment", $paymentMethod);


        $qb->setFirstResult(0);
        $qb->setMaxResults(1);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

}