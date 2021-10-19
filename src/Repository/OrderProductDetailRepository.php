<?php

namespace App\Repository;

use App\Entity\CustomerOrder;
use App\Entity\OrderProductDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderProductDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProductDetail::class);
    }

    public function getProductsDetailByOrder(CustomerOrder $order)
    {
        $qb = $this->createQueryBuilder("order_product_detail");

        $qb->andWhere("order_product_detail.order = :order");
        $qb->setParameter("order", $order);

        $query = $qb->getQuery();

        return $query->getResult();
    }
}