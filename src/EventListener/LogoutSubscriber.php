<?php

namespace Kowada\AuthBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Shows a goodbye flash message after a successful logout.
 */
readonly class LogoutSubscriber implements EventSubscriberInterface {

    public function __construct(
        private RequestStack $requestStack,
        private TranslatorInterface $translator
    ) {}

    /**
     * @return array<class-string, string> The event-to-method mapping required by {@see EventSubscriberInterface}.
     */
    public static function getSubscribedEvents(): array {
        return [
            LogoutEvent::class => 'onLogout',
        ];
    }

    public function onLogout(LogoutEvent $event): void {
        $session = $this->requestStack->getCurrentRequest()?->getSession();

        if ($session instanceof FlashBagAwareSessionInterface) {
            $session->getFlashBag()->add('info', $this->translator->trans('goodbye', domain: 'kowada_auth'));
        }
    }

}
