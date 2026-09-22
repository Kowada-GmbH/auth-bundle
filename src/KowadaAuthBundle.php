<?php

namespace Kowada\AuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * The bundle class registering Kowada's shared auth building blocks with the kernel.
 */
class KowadaAuthBundle extends Bundle {

    /**
     * @return string The bundle's root directory (one level up from `src/`), used to locate `translations/`.
     */
    public function getPath(): string {
        return \dirname(__DIR__);
    }

}
