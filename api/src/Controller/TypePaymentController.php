<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TypePaymentRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TypePaymentController extends AbstractController
{
    public function __construct(
        private readonly TypePaymentRepositoryInterface $typePaymentRepository,
    ) {
    }

    #[Route('/payment-types', name: 'list_payment_types', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $paymentTypes = $this->typePaymentRepository->getTypePaymentList();
        if(!$paymentTypes) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        return $this->json($paymentTypes, Response::HTTP_ACCEPTED, [], [
            'groups' => ['typePayments.list'],
        ]);
    }
}
