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
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{

    /**
     * @Route("/receipts", name="get_receipts")
     * @Method({"GET"})
     * @param ReceiptManagementService $receiptMng
     * @param Request $request
     * @return JsonResponse
     */
    public function receiptsAction(ReceiptManagementService $receiptMng, Request $request)
    {

        if ($request->getMethod() === "OPTIONS") {
            return new CustomResponse(null);
        }

        try
        {
            return $receiptMng->loadReceipts();
        }
        catch (\Exception $e)
        {
            return new CustomResponse($e->getMessage(), false);
        }
    }

    /**
     * @Route("/receipt/{id}", name="post_receipt")
     * @Method({"POST"})
     * @param $id
     * @param ReceiptManagementService $receiptMng
     * @param Request $request
     * @param Receipt $receipt
     * @return JsonResponse
     */
    public function receiptAction($id, ReceiptManagementService $receiptMng, Request $request, Receipt $receipt = null)
    {

        if ($request->getMethod() === "OPTIONS") {
            return new CustomResponse(null);
        }

        try
        {

            $content = json_decode($request->getContent(), true);

            switch ($content["action"]) {

                case "save":
                    if ($receipt === null && $id !== "null") {
                        throw new \Exception("Receipt with id = " . $id . " not found.");
                    }
                    return $receiptMng->saveReceipt($receipt === null ? new Receipt() : $receipt, $content["receipt"]);

                case "delete":
                    if ($receipt === null) {
                        throw new \Exception("Receipt with id = " . $id . " not found.");
                    }
                    return $receiptMng->deleteReceipt($receipt);

                default:
                    throw new \Exception("Unknown action: " .$content["action"]);
            }

        }
        catch (\Exception $e)
        {
            return new CustomResponse($e->getMessage(), false);
        }

    }

    /**
     * @Route("/stores", name="get_stores")
     * @Method({"GET"})
     * @param StoreManagementService $storeMng
     * @param Request $request
     * @return JsonResponse
     */
    public function storesAction(StoreManagementService $storeMng, Request $request)
    {
        if ($request->getMethod() === "OPTIONS") {
            return new CustomResponse(null);
        }

        try
        {
            return $storeMng->loadStores();
        }
        catch (\Exception $e)
        {
            return new CustomResponse($e->getMessage(), false);
        }
    }

}
