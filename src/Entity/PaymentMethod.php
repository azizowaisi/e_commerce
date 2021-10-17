<?php

namespace App\Entity;

class PaymentMethod
{
    private $id;
    private $paymentKey;
    private $createdAt;
    private $updatedAt;
    private $method;
    private $authorizedId;

    private $customer;

    public function __construct()
    {
        $this->paymentKey = uniqid();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getPaymentKey(): string
    {
        return $this->paymentKey;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getAuthorizedId()
    {
        return $this->authorizedId;
    }

    /**
     * @param mixed $authorizedId
     */
    public function setAuthorizedId($authorizedId): void
    {
        $this->authorizedId = $authorizedId;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer): void
    {
        $this->customer = $customer;
    }
}