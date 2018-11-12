<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Receipt;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Store;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('receipts.html');
    }

    /**
     * @Route("/receipts", name="get_receipts")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function receiptsAction(Request $request)
    {
        $page = $request->query->get("page", 1);
        $pageSize = $request->query->get("pageSize", 5);

        $repository = $this->getDoctrine()->getRepository(Receipt::class);

        $totalReceipts = $repository->createQueryBuilder('r')
            ->select('count(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

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
     * @Route("/receipt/delete/{id}", name="delete_receipt")
     * @Method({"GET"})
     * @param $id
     * @param Receipt $receipt
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteReceiptAction($id, Receipt $receipt = null)
    {
        if ($receipt === null) {
            throw new \Exception("Receipt with id = " . $id . " not found.");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($receipt);
        $em->flush();

        return new JsonResponse(true, Response::HTTP_OK);
    }

    /**
     * @Route("/receipt/{id}", name="post_receipt")
     * @Method({"POST"})
     * @param Receipt $receipt
     * @return JsonResponse
     */
    public function receiptAction(Receipt $receipt = null)
    {

        $request = Request::createFromGlobals();
        $content = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        if ($receipt === null) {
            $receipt = new Receipt();
            $em->persist($receipt);
        }

        $receipt->setDate(DateTime::createFromFormat('d-m-Y', $content["date"]));

        $storeName = $content["store"]["name"];
        if ($receipt->getStore()->getName() != $storeName) {
            $store = $em->getRepository(Store::class)->findOneBy(array("name" => $storeName));
            if ($store === null) {
                $store = new Store();
                $store->setName($storeName);
                $em->persist($store);
            }
            $receipt->setStore($store);
        }

        $receipt->clearArticles();
        $articlesArray = $content["articles"];
        foreach ($articlesArray as $articleData)
        {
            $article = new Article();
            $em->persist($article);
            $article->setReceipt($receipt);
            $article->setName($articleData["name"]);
            $article->setVolume($articleData["volume"]);
            $article->setPrice($articleData["price"]);

            $receipt->addArticle($article);
        }

        $em->flush();

        return new JsonResponse(true, Response::HTTP_OK);

    }

    /**
     * @Route("/stores", name="get_stores")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function storesAction()
    {
        $stores = $this->getDoctrine()->getManager()->getRepository(Store::class)->findAll();
        $data = array();
        foreach ($stores as $store) {
            array_push($data, $store->getJSon());
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

}
