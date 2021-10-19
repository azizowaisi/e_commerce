<?php

namespace App\Repository;


use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Helper\CustomerHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerAddress::class);
    }

    public function getCustomerAddressByType(Customer $customer, string $addressType = CustomerHelper::ADDRESS_TYPE_BILLING)
    {
        $qb = $this->createQueryBuilder("ca");

        $qb->andWhere("ca.customer = :customer");
        $qb->setParameter("customer", $customer);

        $qb->andWhere("ca.type = :addressType");
        $qb->setParameter("addressType", $addressType);

        $qb->setFirstResult(0);
        $qb->setMaxResults(1);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
}