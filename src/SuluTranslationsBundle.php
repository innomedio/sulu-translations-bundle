<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle;

use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\DependencyInjection\SuluTranslationsExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SuluTranslationsBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new SuluTranslationsExtension();
    }
}
