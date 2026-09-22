<?php

namespace Kowada\AuthBundle\Tests\Fixtures;

use DateTimeInterface;
use Kowada\AuthBundle\Security\LoginTrackingInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DummyLoginTrackingUser implements UserInterface, LoginTrackingInterface {

    private ?DateTimeInterface $dateTimeLoggedInInitial = null;

    private ?DateTimeInterface $dateTimeLoggedInLatest = null;

    public function __construct(
        private readonly string $firstname
    ) {}

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function getDateTimeLoggedInInitial(): ?DateTimeInterface {
        return $this->dateTimeLoggedInInitial;
    }

    public function setDateTimeLoggedInInitial(DateTimeInterface $dateTime): void {
        $this->dateTimeLoggedInInitial = $dateTime;
    }

    public function setDateTimeLoggedInLatest(DateTimeInterface $dateTime): void {
        $this->dateTimeLoggedInLatest = $dateTime;
    }

    public function getDateTimeLoggedInLatest(): ?DateTimeInterface {
        return $this->dateTimeLoggedInLatest;
    }

    public function getRoles(): array {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string {
        return 'dummy';
    }

}
