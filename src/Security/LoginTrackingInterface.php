<?php

namespace Kowada\AuthBundle\Security;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Implemented by the consuming application's user class to let {@see \Kowada\AuthBundle\EventListener\LoginSuccessSubscriber}
 * record login timestamps and show a welcome message.
 */
interface LoginTrackingInterface {

    /**
     * @return string The user's first name, used in the welcome message.
     */
    public function getFirstname(): string;

    /**
     * @return DateTimeInterface|null The moment of the user's first successful login, or null before it has happened.
     */
    public function getDateTimeLoggedInInitial(): ?DateTimeInterface;

    /**
     * @param DateTimeImmutable $dateTime The moment of the user's first successful login.
     *
     * @return mixed No return type is declared so both void and fluent (self-returning) setters can implement it.
     */
    public function setDateTimeLoggedInInitial(DateTimeImmutable $dateTime);

    /**
     * @param DateTimeImmutable $dateTime The moment of the user's most recent successful login.
     *
     * @return mixed No return type is declared so both void and fluent (self-returning) setters can implement it.
     */
    public function setDateTimeLoggedInLatest(DateTimeImmutable $dateTime);

}
