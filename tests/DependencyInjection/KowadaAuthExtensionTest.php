<?php

namespace Kowada\AuthBundle\Tests\DependencyInjection;

use Kowada\AuthBundle\DependencyInjection\KowadaAuthExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KowadaAuthExtensionTest extends TestCase {

    public function testAliasMatchesConfigurationRootName(): void {
        $extension = new KowadaAuthExtension();

        $this->assertSame('kowada_auth', $extension->getAlias());
    }

    public function testLoadCompilesContainerWithoutErrors(): void {
        $this->expectNotToPerformAssertions();

        $container = new ContainerBuilder();
        $extension = new KowadaAuthExtension();

        $extension->load([], $container);

        $container->compile();
    }

}
