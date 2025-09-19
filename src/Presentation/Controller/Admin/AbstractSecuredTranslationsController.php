<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Presentation\Controller\Admin;

use Phpro\SuluTranslationsBundle\Infrastructure\Sulu\Admin\TranslationsAdmin;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function getSecurityContext(): string
    {
        return TranslationsAdmin::SECURITY_CONTEXT;
    }

    public function getLocale(Request $request): string
    {
        return $request->getLocale();
    }
}
