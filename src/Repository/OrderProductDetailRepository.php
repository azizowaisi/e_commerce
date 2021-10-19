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

    public function getCustomerOrderByNameAndQuantity(CustomerOrder $order, string $name, string $quantity)
    {
        $qb = $this->createQueryBuilder("order_product_detail");

        $qb->andWhere("order_product_detail.order = :order");
        $qb->setParameter("order", $order);

        $qb->andWhere("order_product_detail.productName = :name");
        $qb->setParameter("name", $name);

        $qb->andWhere("order_product_detail.quantity = :quantity");
        $qb->setParameter("quantity", $quantity);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
}