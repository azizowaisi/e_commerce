<?php

namespace App\Helper;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Entity\PaymentMethod;
use App\Repository\CustomerAddressRepository;
use App\Repository\CustomerRepository;
use App\Repository\PaymentMethodRepository;
use Doctrine\ORM\EntityManagerInterface;

class CustomerHelper
{
    const ADDRESS_TYPE_BILLING = "billing";
    const ADDRESS_TYPE_SHIPPING = "shipping";

    private $entityManager;
    private $customerRepository;
    private $customerAddressRepository;
    private $paymentMethodRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CustomerRepository $customerRepository,
        CustomerAddressRepository $customerAddressRepository,
        PaymentMethodRepository $paymentMethodRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->customerRepository = $customerRepository;
        $this->customerAddressRepository = $customerAddressRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function getCustomerFromJson(array $jsonData)
    {
        if (!array_key_exists("customer", $jsonData)) {
            return null;
        }

        $customerData = $jsonData['customer'];

        if (!array_key_exists("email", $customerData)) {
            return null;
        }

        $email = $customerData['email'];
        $customer = $this->getCustomer($email);

        if (!array_key_exists("billing_address", $jsonData)) {
            return $customer;
        }

        $billingAddress = $jsonData['billing_address'];
        $this->updateAddress($customer, $billingAddress, self::ADDRESS_TYPE_BILLING);

        if (!array_key_exists("shipping_address", $jsonData)) {
            return $customer;
        }

        $shippingAddress = $jsonData['shipping_address'];
        $this->updateAddress($customer, $shippingAddress, self::ADDRESS_TYPE_SHIPPING);


        $this->updatePaymentMethod($customer, $jsonData);

        return $customer;
    }

    private function getCustomer(string $email) : Customer
    {
        $customer = $this->customerRepository->getCustomerByEmail($email);
        if ($customer instanceof Customer) {
            return $customer;
        }

        $customer = new Customer();
        $customer->setEmail($email);
        $customer->setName($email);
        $customer->setCreatedAt(new \DateTime("now"));
        $customer->setUpdatedAt(new \DateTime("now"));

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $customer;
    }

    private function updateAddress(Customer $customer, array $address, string $type )
    {
        $customerAddress = $this->customerAddressRepository->getCustomerAddressByType($customer, $type);
        if(!$customerAddress instanceof CustomerAddress){
            $customerAddress = new CustomerAddress();
            $customerAddress->setCustomer($customer);
            $customerAddress->setType($type);
        }

        if (array_key_exists("name", $address)) {
            $customerAddress->setName($address['name']);
        }

        if (array_key_exists("country", $address)) {
            $customerAddress->setCountry($address['country']);
        }

        if (array_key_exists("postcode", $address)) {
            $customerAddress->setPostCode($address['postcode']);
        }

        if (array_key_exists("city", $address)) {
            $customerAddress->setCity($address['city']);
        }

        if (array_key_exists("street", $address)) {
            $customerAddress->setStreet($address['street']);
        }

        $this->entityManager->persist($customerAddress);

        $customer->setUpdatedAt(new \DateTime("now"));
        $this->entityManager->persist($customer);

        $this->entityManager->flush();

        return $customerAddress;
    }

    public function updatePaymentMethod(Customer $customer, array $jsonData)
    {
        if (!array_key_exists("payment", $jsonData)) {
            return null;
        }

        $payment = $jsonData['payment'];

        if (!array_key_exists("method", $payment)) {
            return null;
        }

        $method = $payment['method'];

        if (!array_key_exists("authorization_id", $payment)) {
            return null;
        }

        $authorizationId = $payment['authorization_id'];

        $paymentMethod = $this->paymentMethodRepository->getPaymentMethod($customer, $method, $authorizationId);
        if(!$paymentMethod instanceof PaymentMethod){

            $paymentMethod = new PaymentMethod();
            $paymentMethod->setCustomer($customer);
            $paymentMethod->setCreatedAt(new \DateTime("now"));
            $paymentMethod->setMethod($method);
            $paymentMethod->setAuthorizedId($authorizationId);
        }

        $paymentMethod->setUpdatedAt(new \DateTime("now"));
        $this->entityManager->persist($paymentMethod);

        $customer->setUpdatedAt(new \DateTime("now"));
        $this->entityManager->persist($customer);

        $this->entityManager->flush();

        return $paymentMethod;
    }

}