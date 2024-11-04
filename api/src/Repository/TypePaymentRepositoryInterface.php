<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TypePayment;

interface TypePaymentRepositoryInterface
{
    /** @return TypePayment[] */
    public function getTypePaymentList(): array;

    public function getTypePaymentById(int $id): ?TypePayment;
}
