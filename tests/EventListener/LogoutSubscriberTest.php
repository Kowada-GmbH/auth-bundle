<?php

namespace Kowada\AuthBundle\Tests\EventListener;

use Kowada\AuthBundle\EventListener\LogoutSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class LogoutSubscriberTest extends TestCase {

    public function testAddsFlashMessageWhenRequestAvailable(): void {
        $session = new Session(new MockArraySessionStorage());

        $request = new Request();
        $request->setSession($session);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturn('Auf Wiedersehen!');

        (new LogoutSubscriber($requestStack, $translator))->onLogout($this->createStub(LogoutEvent::class));

        $this->assertSame(['Auf Wiedersehen!'], $session->getFlashBag()->get('info'));
    }

    public function testDoesNothingWithoutCurrentRequest(): void {
        $subscriber = new LogoutSubscriber(new RequestStack(), $this->createStub(TranslatorInterface::class));

        $subscriber->onLogout($this->createStub(LogoutEvent::class));

        $this->addToAssertionCount(1);
    }

}
