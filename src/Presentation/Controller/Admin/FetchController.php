<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Presentation\Controller\Admin;

use Phpro\SuluTranslationsBundle\Domain\Query\FetchTranslation;
use Phpro\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/translations/{id}', name: 'phpro.translations_fetch', options: ['expose' => true], methods: ['GET'])]
final class FetchController extends AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function __construct(
        private readonly FetchTranslation $fetchTranslation,
        private readonly TranslationSerializer $serializer,
    ) {
    }

    public function __invoke(int $id): Response
    {
        return new JsonResponse(
            ($this->serializer)(
                ($this->fetchTranslation)($id)
            )
        );
    }
}
