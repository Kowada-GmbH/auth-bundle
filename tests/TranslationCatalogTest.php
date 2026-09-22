<?php

namespace Kowada\AuthBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class TranslationCatalogTest extends TestCase {

    public function testGermanCatalogTranslatesKnownKeys(): void {
        $translator = $this->createTranslator('de');

        $this->assertSame(
            'Auf Wiedersehen!',
            $translator->trans('goodbye', domain: 'kowada_auth')
        );
    }

    public function testEnglishCatalogTranslatesKnownKeys(): void {
        $translator = $this->createTranslator('en');

        $this->assertSame(
            'Goodbye!',
            $translator->trans('goodbye', domain: 'kowada_auth')
        );
    }

    public function testWelcomeMessageInterpolatesFirstname(): void {
        $translator = $this->createTranslator('en');

        $message = $translator->trans('welcome_first_login', ['%firstname%' => 'Tilo'], 'kowada_auth');

        $this->assertSame('Welcome, Tilo!', $message);
    }

    private function createTranslator(string $locale): Translator {
        $translator = new Translator($locale);
        $translator->addLoader('yaml', new YamlFileLoader());
        $translator->addResource('yaml', dirname(__DIR__) . '/translations/kowada_auth.' . $locale . '.yaml', $locale, 'kowada_auth');

        return $translator;
    }

}
