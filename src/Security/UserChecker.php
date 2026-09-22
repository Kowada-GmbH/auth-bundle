<?php

namespace Kowada\AuthBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Blocks authentication for users implementing {@see AccountStatusInterface} whose e-mail is unverified or who are not yet approved.
 *
 * Users not implementing the interface are left untouched. Register as the firewall's `user_checker`.
 */
class UserChecker implements UserCheckerInterface {

    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    /**
     * @throws CustomUserMessageAccountStatusException If the user's e-mail is unverified or the account is not approved.
     */
    public function checkPreAuth(UserInterface $user): void {
        if (!$user instanceof AccountStatusInterface) {
            return;
        }

        if (!$user->isEmailVerified()) {
            throw new CustomUserMessageAccountStatusException($this->translator->trans('email_not_verified', domain: 'kowada_auth'));
        }

        if (!$user->isApproved()) {
            throw new CustomUserMessageAccountStatusException($this->translator->trans('account_not_approved', domain: 'kowada_auth'));
        }
    }

    public function checkPostAuth(UserInterface $user, ?TokenInterface $token = null): void {}

}
