# Kowada Auth Bundle

![CI](https://github.com/Kowada-GmbH/auth-bundle/workflows/CI/badge.svg)
![PHP](https://img.shields.io/badge/PHP-%3E%3D8.3-777BB4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-%5E7.4-000000?logo=symfony&logoColor=white)

Shared Symfony bundle with auth building blocks: account-status gating, login tracking, and login/logout flash messages - all wired to your own User entity via interfaces, instead of requiring a bundle-owned User class.

The bundle is maintained here as a versioned Composer dependency and pulled into individual Symfony projects via `composer update kowada-gmbh/auth-bundle`.

## Installation

Since this is a private package, the repository must be registered as a VCS repository in the consuming project:

```console
composer config repositories.kowada-auth-bundle vcs https://github.com/kowada-gmbh/auth-bundle.git
```

The package can then be required as a regular dependency:

```console
composer require kowada-gmbh/auth-bundle ^1.0
```

## Features

All classes under `Kowada\AuthBundle\` are automatically registered as services via autowiring/autoconfiguration (see [config/services.yaml](config/services.yaml)).

- [`Security\AccountStatusInterface`](src/Security/AccountStatusInterface.php) + [`Security\UserChecker`](src/Security/UserChecker.php): blocks login when `isEmailVerified()` or `isApproved()` return `false`.
- [`Security\LoginTrackingInterface`](src/Security/LoginTrackingInterface.php) + [`EventListener\LoginSuccessSubscriber`](src/EventListener/LoginSuccessSubscriber.php): records the first/latest login timestamp and shows a welcome flash message.
- [`EventListener\LogoutSubscriber`](src/EventListener/LogoutSubscriber.php): shows a goodbye flash message.

**Configuration in the consuming project:**

1. Your own User entity must implement the interfaces you want to use. Both are independent of each other — an interface that isn't implemented is simply skipped via an `instanceof` check, nothing happens in that case:
   ```php
   class User implements UserInterface, AccountStatusInterface, LoginTrackingInterface {
       public function isEmailVerified(): bool { /* ... */ }
       public function isApproved(): bool { /* ... */ }

       public function getFirstname(): string { /* ... */ }
       public function getDateTimeLoggedInInitial(): ?DateTimeInterface { /* ... */ }
       public function setDateTimeLoggedInInitial(DateTimeImmutable $dateTime) { /* ... */ }
       public function setDateTimeLoggedInLatest(DateTimeImmutable $dateTime) { /* ... */ }
   }
   ```
2. Register `UserChecker` on the relevant firewall in `security.yaml`:
   ```yaml
   security:
       firewalls:
           main:
               user_checker: Kowada\AuthBundle\Security\UserChecker
   ```
3. `LoginSuccessSubscriber`/`LogoutSubscriber` are event subscribers and become active without further configuration. A session-based firewall (`FlashBagAwareSessionInterface`) is assumed — with stateless APIs the flash messages simply have no effect, but no error occurs either.

### Translations

All text goes through the Symfony translator, domain `kowada_auth` ([de](translations/kowada_auth.de.yaml) / [en](translations/kowada_auth.en.yaml)). Translations are loaded automatically as soon as the translator is enabled in the consuming project (`framework.translator`) — no further configuration needed. Individual keys can be overridden in the consuming project by creating a same-named `translations/kowada_auth.<locale>.yaml` file there.

## Development

```console
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse
```
