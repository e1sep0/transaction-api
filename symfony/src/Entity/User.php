<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class User
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="integer", options={default: 0})
     * @Assert\GreaterThan(0)
     */
    private int $balance;

    /**
     * @ORM\OneToMany(targetEntity=BalanceHistory::class, mappedBy="user", orphanRemoval=true)
     */
    private $balanceHistories;

    public function __construct()
    {
        $this->balanceHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return BalanceHistory[]|Collection
     */
    public function getBalanceHistories(): Collection
    {
        return $this->balanceHistories;
    }

    public function addBalanceHistory(BalanceHistory $balanceHistory): self
    {
        if (!$this->balanceHistories->contains($balanceHistory)) {
            $this->balanceHistories[] = $balanceHistory;
            $balanceHistory->setUser($this);
        }

        return $this;
    }

    public function removeBalanceHistory(BalanceHistory $balanceHistory): self
    {
        if ($this->balanceHistories->removeElement($balanceHistory)) {
            // set the owning side to null (unless already changed)
            if ($balanceHistory->getUser() === $this) {
                $balanceHistory->setUser(null);
            }
        }

        return $this;
    }
}
