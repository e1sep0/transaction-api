<?php

declare(strict_types=1);

namespace App\Request;

use App\Service\RequestResolver\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Validator\Constraints as Assert;

class TransactionRequest implements RequestDTOInterface
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     */
    private ?int $senderId;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     */
    private ?int $recipientId;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\GreaterThan(0)
     */
    private ?int $amount;

    public function __construct(Request $request)
    {
        $decoder = new JsonDecode();
        $data = $decoder->decode($request->getContent(), 'json');

        $this->amount = $data->amount ?? null;
        $this->senderId = $data->senderId ?? null;
        $this->recipientId = $data->recipientId ?? null;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function getRecipientId(): int
    {
        return $this->recipientId;
    }
}
