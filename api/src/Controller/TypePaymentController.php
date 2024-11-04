<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TypePayment;
use App\Repository\TypePaymentRepositoryInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

final class TypePaymentController extends AbstractController
{
    public function __construct(
        private readonly TypePaymentRepositoryInterface $typePaymentRepository,
    ) {
    }

    #[Route('/payment-types', name: 'list_payment_types', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns payment types list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TypePayment::class))
        )
    )]
    #[OA\Tag(name: 'Payment Types')]
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
