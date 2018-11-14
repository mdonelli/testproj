<?php

namespace AppBundle\Service;


use AppBundle\Common\CustomResponse;
use AppBundle\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;

class StoreManagementService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return CustomResponse
     */
    public function loadStores()
    {
        $stores = $this->em->getRepository(Store::class)->findAll();
        $data = array();
        foreach ($stores as $store) {
            array_push($data, $store->getJSon());
        }

        return new CustomResponse($data);
    }

}