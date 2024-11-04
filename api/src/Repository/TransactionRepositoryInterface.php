<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Transaction;

interface TransactionRepositoryInterface
{
    /** @return Transaction[] */
    public function getTransactionList(): array;

    public function getTransactionById(int $id): ?Transaction;

    public function save(Transaction $transaction): void;

    public function delete(Transaction $transaction): void;
}
