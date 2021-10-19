<?php

namespace App\Controller;

use App\Helper\OrderHelper;
use App\Helper\UtilHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    public function index(Request $request, OrderHelper $orderHelper)
    {
        if ($request->getMethod() != "POST") {
            return new Response("Method not allowed", "406");
        }

        $apiKey = $request->headers->get("key");
        if (UtilHelper::isEmpty($apiKey)) {
            return new Response("Authentication key is missing", "401");
        }

        if ("f2eaa98f198b9f37bbe0eac0b7981852f2cbaa93989535fd09ed4b7972659e1d" != $apiKey) {
            return new Response("Access denied", "403");
        }

        $jsonString = $request->getContent();
        if (empty($jsonString)) {
            return new Response("Empty request", "406");
        }


        //try to load JSON
        try {

            $jsonData = json_decode($jsonString, true);

        } catch (\Exception $exception) {
            return new Response("Invalid JSON", "400");
        }

        //try to read JSON
        try {

            $response = $orderHelper->handleOrder($jsonData);
            if(!$response){
                return new Response("Order already exists", "201");
            }

        } catch (\Exception $exception) {
            return new Response("Invalid JSON", "400");
        }

        return new Response("Your order is placed, you will received an email for confirmation", "200");

    }

    public function second()
    {
        dump("second");
        die;
    }

}