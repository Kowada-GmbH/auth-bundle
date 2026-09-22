<?php

namespace Kowada\AuthBundle\EventListener;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Kowada\AuthBundle\Security\LoginTrackingInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Records login timestamps and shows a welcome flash message for users implementing {@see LoginTrackingInterface}.
 *
 * Users not implementing the interface are left untouched.
 */
readonly class LoginSuccessSubscriber implements EventSubscriberInterface {

    public function __construct(
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack,
        private TranslatorInterface $translator
    ) {}

    /**
     * @return array<class-string, string> The event-to-method mapping required by {@see EventSubscriberInterface}.
     */
    public static function getSubscribedEvents(): array {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void {
        $user = $event->getUser();

        if (!$user instanceof LoginTrackingInterface) {
            return;
        }

        $isFirstLogin = $user->getDateTimeLoggedInInitial() === null;

        $message = $this->translator->trans(
            $isFirstLogin ? 'welcome_first_login' : 'welcome_back',
            ['%firstname%' => $user->getFirstname()],
            'kowada_auth'
        );

        if ($isFirstLogin) {
            $user->setDateTimeLoggedInInitial(new DateTimeImmutable());
        }

        $user->setDateTimeLoggedInLatest(new DateTimeImmutable());

        $this->entityManager->flush();

        $session = $this->requestStack->getCurrentRequest()?->getSession();

        if ($session instanceof FlashBagAwareSessionInterface) {
            $session->getFlashBag()->add('success', $message);
        }
    }

}
