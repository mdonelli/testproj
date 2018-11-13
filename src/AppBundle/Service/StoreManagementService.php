<?php

namespace AppBundle\Service;


use AppBundle\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoreManagementService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function loadStores()
    {
        $stores = $this->em->getRepository(Store::class)->findAll();
        $data = array();
        foreach ($stores as $store) {
            array_push($data, $store->getJSon());
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

}