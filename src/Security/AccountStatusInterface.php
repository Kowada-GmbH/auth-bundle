<?php

namespace Kowada\AuthBundle\Security;

/**
 * Implemented by the consuming application's user class to let {@see UserChecker} block login for
 * unverified or not-yet-approved accounts.
 */
interface AccountStatusInterface {

    /**
     * @return bool Whether the user has confirmed their e-mail address.
     */
    public function isEmailVerified(): bool;

    /**
     * @return bool Whether the user has been approved (e.g. by an administrator) and may log in.
     */
    public function isApproved(): bool;

}
