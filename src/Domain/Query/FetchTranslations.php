<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Query;

use Phpro\SuluTranslationsBundle\Domain\Model\TranslationList;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

class FetchTranslations
{
    public function __construct(
        private readonly TranslationRepository $repository,
    ) {
    }

    public function __invoke(SearchCriteria $criteria): TranslationList
    {
        return new TranslationList(
            $this->repository->findByCriteria($criteria),
            $this->repository->countByCriteria($criteria),
        );
    }
}
