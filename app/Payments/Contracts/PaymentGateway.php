<?php

namespace App\Payments\Contracts;

use App\Models\Order;

interface PaymentGateway
{
    public function createPayment(Order $order): string;
    public function handleReturn(array $payload): array;

    public function handleWebhook(
        array $payload,
        ?string $signature = null
    ): array;
}
