<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\TransactionRequest;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="make_transaction", methods={"POST"})
     */
    public function makeTransaction(TransactionRequest $request, TransactionService $transactionService): JsonResponse
    {
        $transactionService->createTransaction($request);

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
