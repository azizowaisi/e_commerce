<?php

namespace App\Helper;

use App\Entity\Customer;
use App\Entity\CustomerOrder;
use App\Entity\OrderProductDetail;
use App\Entity\PaymentMethod;
use App\Repository\CustomerOrderRepository;
use App\Repository\OrderProductDetailRepository;
use Doctrine\ORM\EntityManagerInterface;
use function Doctrine\ORM\QueryBuilder;

class OrderHelper
{
    private $entityManager;
    private $customerHelper;
    private $customerOrderRepository;
    private $orderProductDetailRepository;

    public function __construct(
        EntityManagerInterface       $entityManager,
        CustomerHelper               $customerHelper,
        CustomerOrderRepository      $customerOrderRepository,
        OrderProductDetailRepository $orderProductDetailRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->customerHelper = $customerHelper;
        $this->customerOrderRepository = $customerOrderRepository;
        $this->orderProductDetailRepository = $orderProductDetailRepository;
    }


    public function handleOrder(array $jsonData)
    {
        $customer = $this->customerHelper->getCustomerFromJson($jsonData);
        if (!$customer instanceof Customer) {
            return false;
        }

        $paymentMethod = $this->customerHelper->updatePaymentMethod($customer, $jsonData);
        if (!$paymentMethod instanceof PaymentMethod) {
            return false;
        }

        return $this->placeOrder($customer, $paymentMethod, $jsonData);
    }

    public function placeOrder(Customer $customer, PaymentMethod $paymentMethod, array $jsonData)
    {
        $customerOrder = $this->orderAlreadyExists($customer, $paymentMethod, $jsonData);
        dump($customerOrder);
        die;

        $customerOrder = $this->customerOrderRepository->getOrder($customer, $paymentMethod);
        if ($customerOrder instanceof CustomerOrder) {
            return false;
        }

        $customerOrder = new CustomerOrder();
        $customerOrder->setCustomer($customer);
        $customerOrder->setCreatedAt(new \DateTime("now"));
        $customerOrder->setPaymentMethod($paymentMethod);

        $this->entityManager->persist($customerOrder);
        $this->entityManager->flush();

        $this->placeOrderDetail($customerOrder, $jsonData);

        return true;
    }

    public function placeOrderDetail(CustomerOrder $order, array $jsonData)
    {
        $productProducts = $this->orderProductDetailRepository->getProductsDetailByOrder($order);
        if (!empty($productProducts)) {
            return;
        }

        if (!array_key_exists("products", $jsonData)) {
            return;
        }

        $products = $jsonData['products'];
        foreach ($products as $product) {

            if (empty($product)) {
                continue;
            }

            $this->createOrderDetails($order, $product);
        }
    }

    public function createOrderDetails(CustomerOrder $order, $product)
    {
        if (!array_key_exists("sku", $product)) {
            return null;
        }

        $productName = $product['sku'];

        if (!array_key_exists("qty", $product)) {
            return null;
        }

        $quantity = $product['qty'];

        $orderProductDetail = new OrderProductDetail();
        $orderProductDetail->setOrder($order);
        $orderProductDetail->setProductName($productName);
        $orderProductDetail->setQuantity($quantity);

        $this->entityManager->persist($orderProductDetail);
        $this->entityManager->flush();
    }

    public function orderAlreadyExists(Customer $customer, PaymentMethod $paymentMethod, array $jsonData)
    {

        $products = $jsonData['products'];

        $repository = $this->entityManager->getRepository("App:CustomerOrder");
        $qb = $repository->createQueryBuilder("customerOrder");

        $qb->join("customerOrder.products", "product");

        $qb->andWhere("customerOrder.customer = :customer");
        $qb->setParameter("customer", $customer);

        $qb->andWhere("customerOrder.paymentMethod = :payment");
        $qb->setParameter("payment", $paymentMethod);


        $orx = $qb->expr()->orX();
        foreach ($products as $product) {
            if (empty($product)) {
                continue;
            }

            if (!array_key_exists("sku", $product)) {
                return null;
            }

            $productName = $product['sku'];

            if (!array_key_exists("qty", $product)) {
                return null;
            }

            $quantity = $product['qty'];


            $orx->add($qb->expr()->eq("product.productName", "'".$productName."'"));
            $orx->add($qb->expr()->eq("product.quantity", $quantity));
        }

        $qb->andWhere($orx);

        $qb->setFirstResult(0);
        $qb->setMaxResults(1);

        $query = $qb->getQuery();




        return $query->getOneOrNullResult();


    }

}