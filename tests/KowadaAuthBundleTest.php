<?php

namespace Kowada\AuthBundle\Tests;

use Kowada\AuthBundle\KowadaAuthBundle;
use PHPUnit\Framework\TestCase;

class KowadaAuthBundleTest extends TestCase {

    public function testGetPathReturnsThePackageRootNotTheSrcDirectory(): void {
        $bundle = new KowadaAuthBundle();

        $path = $bundle->getPath();

        $this->assertDirectoryExists($path . '/translations');
        $this->assertDirectoryExists($path . '/src');
        $this->assertFileDoesNotExist($path . '/KowadaAuthBundle.php');
    }

}
