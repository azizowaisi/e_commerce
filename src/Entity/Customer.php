<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Customer
{
    private $id;
    private $customerKey;
    private $createdAt;
    private $updatedAt;
    private $name;
    private $email;

    private $addresses ;
    private $payments;


    public function __construct()
    {
        $this->customerKey = uniqid();
        $this->addresses = new ArrayCollection();
        $this->payments = new ArrayCollection();
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
    public function getCustomerKey(): string
    {
        return $this->customerKey;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return ArrayCollection
     */
    public function getAddresses(): ArrayCollection
    {
        return $this->addresses;
    }

    /**
     * @param ArrayCollection $addresses
     */
    public function setAddresses(ArrayCollection $addresses): void
    {
        $this->addresses = $addresses;
    }

    /**
     * @return ArrayCollection
     */
    public function getPayments(): ArrayCollection
    {
        return $this->payments;
    }

    /**
     * @param ArrayCollection $payments
     */
    public function setPayments(ArrayCollection $payments): void
    {
        $this->payments = $payments;
    }
}