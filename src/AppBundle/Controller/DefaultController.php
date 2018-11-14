<?php

namespace AppBundle\Controller;

use AppBundle\Common\CustomResponse;
use AppBundle\Entity\Receipt;
use AppBundle\Service\ReceiptManagementService;
use AppBundle\Service\StoreManagementService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{

    /**
     * @Route("/receipts", name="get_receipts")
     * @Method({"GET"})
     * @param ReceiptManagementService $receiptMng
     * @return JsonResponse
     */
    public function receiptsAction(ReceiptManagementService $receiptMng)
    {
        return $receiptMng->loadReceipts();
    }

    /**
     * @Route("/receipt/delete/{id}", name="delete_receipt")
     * @Method({"GET"})
     * @param $id
     * @param Receipt $receipt
     * @param ReceiptManagementService $receiptMng
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteReceiptAction($id, ReceiptManagementService $receiptMng, Receipt $receipt = null)
    {
        $request = Request::createFromGlobals();

        if ($request->getMethod() === "OPTIONS") {
            $response = new CustomResponse(null);
            return $response;
        }

        if ($receipt === null) {
            throw new \Exception("Receipt with id = " . $id . " not found.");
        }

        return $receiptMng->deleteReceipt($receipt);
    }

    /**
     * @Route("/receipt/{id}", name="post_receipt")
     * @Method({"POST"})
     * @param $id
     * @param ReceiptManagementService $receiptMng
     * @param Receipt $receipt
     * @return JsonResponse
     */
    public function receiptAction($id, ReceiptManagementService $receiptMng, Receipt $receipt = null)
    {

        $request = Request::createFromGlobals();

        if ($request->getMethod() === "OPTIONS") {
            $response = new CustomResponse(null);
            return $response;
        }

        $content = json_decode($request->getContent(), true);

        if ($receipt === null) {
            $receipt = new Receipt();
        }

        return $receiptMng->saveReceipt($receipt, $content);
    }

    /**
     * @Route("/stores", name="get_stores")
     * @Method({"GET"})
     * @param StoreManagementService $storeMng
     * @return JsonResponse
     */
    public function storesAction(StoreManagementService $storeMng)
    {
        return $storeMng->loadStores();
    }

}
