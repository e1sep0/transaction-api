<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Transaction;
use App\Entity\User;
use App\Request\TransactionRequest;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class TransactionService
{
    private EntityManagerInterface $entityManager;

    private LoggerInterface $logger;

    /**
     * TransactionService constructor.
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function createTransaction(TransactionRequest $transactionRequest): void
    {
        $this->logger->info('Trying create new transaction', [
            'senderId' => $transactionRequest->getSenderId(),
            'recipientId' => $transactionRequest->getRecipientId(),
            'amount' => $transactionRequest->getAmount(),
        ]);

        $sender = $this->getUser($transactionRequest->getSenderId());
        $recipient = $this->getUser($transactionRequest->getRecipientId());
        $amount = $transactionRequest->getAmount();

        $this->validateTransaction($sender, $recipient, $amount);

        $this->entityManager->beginTransaction();

        try {
            $transaction = new Transaction();
            $transaction
                ->setSender($sender)
                ->setRecipient($recipient)
                ->setAmount($amount)
            ;

            $sender->subBalance($amount);
            $recipient->addBalance($amount);
            $this->entityManager->persist($transaction);
            $this->entityManager->flush();
            $this->entityManager->commit();

            $this->logger->info('Transaction created');
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            $this->logger->error('Transaction error:', [
                'error' => $e->getMessage(),
            ]);

            throw new BadRequestException('Transaction error', Response::HTTP_BAD_REQUEST);
        }
    }

    private function validateTransaction($sender, $recipient, $amount): void
    {
        if (null === $sender) {
            $this->logger->error('Sender user not found');

            throw new BadRequestException('Sender not found', Response::HTTP_BAD_REQUEST);
        }

        if (null === $recipient) {
            $this->logger->error('Recipient user not found');

            throw new BadRequestException('Recipient not found', Response::HTTP_BAD_REQUEST);
        }

        if ($sender === $recipient) {
            $this->logger->error('Sender and recipient are same');

            throw new BadRequestException('Sender and recipient are same', Response::HTTP_BAD_REQUEST);
        }

        if ($sender->getBalance() < $amount) {
            $this->logger->error('Insufficient funds');

            throw new BadRequestException('Insufficient funds', Response::HTTP_BAD_REQUEST);
        }
    }

    private function getUser(int $userId): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($userId);
    }
}
