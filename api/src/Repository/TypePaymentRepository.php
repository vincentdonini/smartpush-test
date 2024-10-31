<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TypePayment;
use Doctrine\ORM\EntityManagerInterface;

final class TypePaymentRepository implements TypePaymentRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getTypePaymentList(): array
    {
        /** @var TypePayment[] */
        return $this->entityManager
            ->createQueryBuilder()
            ->select('t')
            ->from(TypePayment::class, 't')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getTypePaymentById(int $id): ?TypePayment
    {
        /** @var ?TypePayment */
        return $this->entityManager
            ->createQueryBuilder()
            ->select('t')
            ->from(TypePayment::class, 't')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
