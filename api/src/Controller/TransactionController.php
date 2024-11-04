<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\TransactionRepositoryInterface;
use App\Repository\TypePaymentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class TransactionController extends AbstractController
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly TypePaymentRepositoryInterface $typePaymentRepository,
    ) {
    }
    #[Route('/transactions', name: 'list_transactions', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns transactions list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Transaction::class, groups: ['basic']))
        )
    )]
    #[OA\Tag(name: 'Transactions')]
    public function list(): JsonResponse
    {
        $transactions = $this->transactionRepository->getTransactionList();
        if(!$transactions) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        return $this->json($transactions, Response::HTTP_OK, [], [
            'groups' => ['transactions.list'],
        ]);
    }

    #[Route('/transactions/{id}', name: 'detail_transaction', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns transaction details',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Transaction::class, groups: ['extend']))
        )
    )]
    #[OA\Tag(name: 'Transactions')]
    public function detail(int $id): JsonResponse
    {
        $transaction = $this->transactionRepository->getTransactionById($id);
        if(!$transaction) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        return $this->json($transaction, Response::HTTP_OK, [], [
            'groups' => ['transactions.list'],
        ]);
    }

    #[Route('/transactions', name: 'create_transaction', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Create a transaction',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Transaction::class, groups: ['extend']))
        )
    )]
    #[OA\Tag(name: 'Transactions')]
    public function create(Request $request): JsonResponse
    {
        $data   = $request->getPayload()->all();
        $errors = [];

        if (empty($data['label'])) {
            $errors['label'] = 'Label is required and cannot be empty.';
        }

        if (!isset($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
            $errors['amount'] = 'Amount is required and must be a positive number.';
        }

        if (empty($data['typePayment']) || !is_numeric($data['typePayment'])) {
            $errors['typePayment'] = 'Type payment is required and must be a valid numeric identifier.';
        } else {
            $typePayment = $this->typePaymentRepository->getTypePaymentById($data['typePayment']);
            if (!$typePayment) {
                $errors['typePayment'] = 'Type payment provided is invalid.';
            }
        }

        if (!empty($errors)) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $transaction = new Transaction();
        $transaction->setLabel($data['label']);
        $transaction->setAmount($data['amount']);
        $transaction->setTypePayment($typePayment);

        $this->transactionRepository->save($transaction);

        return $this->json($transaction, Response::HTTP_CREATED, [], [
            'groups' => ['transactions.list'],
        ]);
    }

    #[Route('/transactions/{id}', name: 'delete_transaction', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Delete a transaction',
    )]
    #[OA\Tag(name: 'Transactions')]
    public function delete(int $id): JsonResponse
    {
        $transaction = $this->transactionRepository->getTransactionById($id);
        if(!$transaction) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $this->transactionRepository->delete($transaction);

        return $this->json('', Response::HTTP_OK);
    }
}
