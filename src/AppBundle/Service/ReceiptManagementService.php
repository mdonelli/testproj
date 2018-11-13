<?php

namespace AppBundle\Service;

use AppBundle\Entity\Article;
use AppBundle\Entity\Receipt;
use AppBundle\Entity\Store;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class ReceiptManagementService
{

    private $requestStack;
    private $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    /**
     * @return JsonResponse
     */
    public function loadReceipts()
    {
        $request = $this->requestStack->getCurrentRequest();
        $page = $request->query->get("page", 1);
        $pageSize = $request->query->get("pageSize", 5);


        $repository = $this->em->getRepository(Receipt::class);

        $totalReceipts = $repository->count();

        $pageCount = ceil($totalReceipts / $pageSize);

        if ($page > $pageCount) {
            $page = $pageCount;
        }

        $receipts = $repository->createQueryBuilder('r')
            ->orderBy('r.id', 'ASC')
            ->setFirstResult(($page - 1) * $pageSize )
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult();

        $receiptsData = array();
        foreach ($receipts as $receipt)
        {
            array_push($receiptsData, $receipt->getJson());
        }

        $data = array("receipts" => $receiptsData, "pageCount" => $pageCount, "page" => $page, "pageSize" => $pageSize);

        return new JsonResponse( $data,Response::HTTP_OK);
    }

    /**
     * @param Receipt $receipt
     * @return JsonResponse
     */
    public function deleteReceipt(Receipt $receipt)
    {
        $this->em->remove($receipt);
        $this->em->flush();

        return new JsonResponse(true, Response::HTTP_OK);
    }

    public function saveReceipt(Receipt $receipt, $content)
    {

        $this->em->persist($receipt);
        $receipt->setDate(DateTime::createFromFormat('d-m-Y', $content["date"]));

        $storeName = $content["store"]["name"];
        if ($receipt->getStore()->getName() != $storeName) {
            $store = $this->em->getRepository(Store::class)->findOneBy(array("name" => $storeName));
            if ($store === null) {
                $store = new Store();
                $store->setName($storeName);
                $this->em->persist($store);
            }
            $receipt->setStore($store);
        }

        $receipt->clearArticles();
        $articlesArray = $content["articles"];
        foreach ($articlesArray as $articleData)
        {
            $article = new Article();
            $this->em->persist($article);
            $article->setReceipt($receipt);
            $article->setName($articleData["name"]);
            $article->setVolume($articleData["volume"]);
            $article->setPrice($articleData["price"]);

            $receipt->addArticle($article);
        }

        $this->em->flush();

        return new JsonResponse(true, Response::HTTP_OK);
    }
}