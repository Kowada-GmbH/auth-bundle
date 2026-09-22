<?php

namespace Kowada\AuthBundle\Tests\EventListener;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Kowada\AuthBundle\EventListener\LoginSuccessSubscriber;
use Kowada\AuthBundle\Tests\Fixtures\DummyLoginTrackingUser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginSuccessSubscriberTest extends TestCase {

    /**
     * @return array{0: RequestStack, 1: Session}
     */
    private function createSessionBackedRequestStack(): array {
        $session = new Session(new MockArraySessionStorage());

        $request = new Request();
        $request->setSession($session);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        return [$requestStack, $session];
    }

    private function createEvent(mixed $user): LoginSuccessEvent {
        $event = $this->createStub(LoginSuccessEvent::class);
        $event->method('getUser')->willReturn($user);

        return $event;
    }

    public function testSkipsUsersNotImplementingLoginTrackingInterface(): void {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->never())->method('flush');

        [$requestStack] = $this->createSessionBackedRequestStack();

        $subscriber = new LoginSuccessSubscriber($entityManager, $requestStack, $this->createStub(TranslatorInterface::class));

        $subscriber->onLoginSuccess($this->createEvent(new InMemoryUser('user', null)));
    }

    public function testTracksFirstLoginAndAddsFlash(): void {
        $user = new DummyLoginTrackingUser('Tilo');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('flush');

        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnArgument(0);

        [$requestStack, $session] = $this->createSessionBackedRequestStack();

        (new LoginSuccessSubscriber($entityManager, $requestStack, $translator))->onLoginSuccess($this->createEvent($user));

        $this->assertNotNull($user->getDateTimeLoggedInInitial());
        $this->assertNotNull($user->getDateTimeLoggedInLatest());

        $this->assertSame(['welcome_first_login'], $session->getFlashBag()->get('success'));
    }

    public function testTracksReturningLogin(): void {
        $user = new DummyLoginTrackingUser('Tilo');
        $user->setDateTimeLoggedInInitial(new DateTimeImmutable('2020-01-01'));

        $entityManager = $this->createStub(EntityManagerInterface::class);

        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnArgument(0);

        [$requestStack, $session] = $this->createSessionBackedRequestStack();

        (new LoginSuccessSubscriber($entityManager, $requestStack, $translator))->onLoginSuccess($this->createEvent($user));

        $this->assertSame(['welcome_back'], $session->getFlashBag()->get('success'));
    }

}
