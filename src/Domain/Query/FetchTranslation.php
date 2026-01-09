<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Query;

use Phpro\SuluTranslationsBundle\Domain\Model\Translation;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

/**
 * @psalm-suppress ClassMustBeFinal - Mocked in tests
 */
class FetchTranslation
{
    public function __construct(
        private readonly TranslationRepository $repository,
    ) {
    }

    public function __invoke(int $id): Translation
    {
        return $this->repository->findById($id);
    }
}
