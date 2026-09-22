<?php

namespace Kowada\AuthBundle\Tests\Security;

use Kowada\AuthBundle\Security\UserChecker;
use Kowada\AuthBundle\Tests\Fixtures\DummyAccountStatusUser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserCheckerTest extends TestCase {

    private function createChecker(): UserChecker {
        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnArgument(0);

        return new UserChecker($translator);
    }

    public function testSkipsUsersNotImplementingAccountStatusInterface(): void {
        $this->createChecker()->checkPreAuth(new InMemoryUser('user', null));

        $this->addToAssertionCount(1);
    }

    public function testThrowsWhenEmailNotVerified(): void {
        $this->expectException(CustomUserMessageAccountStatusException::class);

        $this->createChecker()->checkPreAuth(new DummyAccountStatusUser(emailVerified: false, approved: true));
    }

    public function testThrowsWhenNotApproved(): void {
        $this->expectException(CustomUserMessageAccountStatusException::class);

        $this->createChecker()->checkPreAuth(new DummyAccountStatusUser(emailVerified: true, approved: false));
    }

    public function testPassesWhenVerifiedAndApproved(): void {
        $this->createChecker()->checkPreAuth(new DummyAccountStatusUser(emailVerified: true, approved: true));

        $this->addToAssertionCount(1);
    }

}
