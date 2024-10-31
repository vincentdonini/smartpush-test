<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getTransactionList(): array
    {
        /** @var Transaction[] */
        return $this->entityManager
            ->createQueryBuilder()
            ->select('t')
            ->from(Transaction::class, 't')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getTransactionById(int $id): ?Transaction
    {
        /** @var ?Transaction */
        return $this->entityManager
            ->createQueryBuilder()
            ->select('t')
            ->from(Transaction::class, 't')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Transaction $transaction): void
    {
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    public function delete(Transaction $transaction): void
    {
        $this->entityManager->remove($transaction);
        $this->entityManager->flush();
    }
}
