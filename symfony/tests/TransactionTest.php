<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\TransactionRequest;
use App\Service\TransactionService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class TransactionTest extends KernelTestCase
{
    private User $sender;
    private User $recipient;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->sender = new User();
        $this->sender->setBalance(100);
        $this->recipient = new User();
        $this->recipient->setBalance(100);

        $userRepository = $this->createMock(UserRepository::class);

        $userRepository->expects(static::exactly(2))
            ->method('find')
            ->willReturnOnConsecutiveCalls($this->sender, $this->recipient)
        ;

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->expects(static::any())
            ->method('getRepository')
            ->willReturn($userRepository)
        ;

        $this->logger = $this->createMock(LoggerInterface::class);

        parent::bootKernel();
    }

    public function testFailTransaction(): void
    {
        $transactionService = new TransactionService($this->logger, $this->entityManager);

        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            json_encode(
            [
                'senderId' => 1,
                'recipientId' => 2,
                'amount' => 500,
            ],
            JSON_THROW_ON_ERROR
        )
        );

        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectErrorMessage('Insufficient funds');

        $transactionService->createTransaction(new TransactionRequest($request));
    }

    public function testSuccessTransaction(): void
    {
        $transactionService = new TransactionService($this->logger, $this->entityManager);

        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            json_encode(
            [
                'senderId' => 1,
                'recipientId' => 2,
                'amount' => 50,
            ],
            JSON_THROW_ON_ERROR
        )
        );

        $transactionService->createTransaction(new TransactionRequest($request));

        static::assertSame(50, $this->sender->getBalance());
        static::assertSame(150, $this->recipient->getBalance());
    }
}
