<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\BalanceHistory;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

class UserBalanceSubscriber implements EventSubscriber
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $this->addBalanceHistory($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getEntity();
        $changeset = $eventArgs->getEntityManager()->getUnitOfWork()->getEntityChangeSet($entity);

        if ($entity instanceof User && isset($changeset['balance'])) {
            $this->logger->info('222');
            $this->addBalanceHistory($entity);
        }
    }

    private function addBalanceHistory(User $user): void
    {
        $balanceHistory = new BalanceHistory();
        $balanceHistory->setBalance($user->getBalance());
        $user->addBalanceHistory($balanceHistory);

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
