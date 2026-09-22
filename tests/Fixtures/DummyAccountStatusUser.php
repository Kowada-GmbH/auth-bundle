<?php

namespace Kowada\AuthBundle\Tests\Fixtures;

use Kowada\AuthBundle\Security\AccountStatusInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class DummyAccountStatusUser implements UserInterface, AccountStatusInterface {

    public function __construct(
        private bool $emailVerified,
        private bool $approved
    ) {}

    public function isEmailVerified(): bool {
        return $this->emailVerified;
    }

    public function isApproved(): bool {
        return $this->approved;
    }

    public function getRoles(): array {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string {
        return 'dummy';
    }

}
