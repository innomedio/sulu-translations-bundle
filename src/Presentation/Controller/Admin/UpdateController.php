<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Presentation\Controller\Admin;

use Phpro\SuluTranslationsBundle\Domain\Command\UpdateCommand;
use Phpro\SuluTranslationsBundle\Domain\Command\UpdateHandler;
use Phpro\SuluTranslationsBundle\Domain\Query\FetchTranslation;
use Phpro\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use function Psl\Type\string;

#[Route(path: '/translations/{id}', name: 'phpro.translations_update', options: ['expose' => true], methods: ['PUT'])]
final class UpdateController extends AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function __construct(
        private readonly UpdateHandler $handler,
        private readonly FetchTranslation $fetchTranslation,
        private readonly TranslationSerializer $serializer,
    ) {
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        ($this->handler)(
            new UpdateCommand($id, string()->coerce($request->request->get('translation')))
        );

        return new JsonResponse(
            ($this->serializer)(
                ($this->fetchTranslation)($id)
            )
        );
    }
}
