<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Repository;

use Phpro\SuluTranslationsBundle\Domain\Model\Translation;
use Phpro\SuluTranslationsBundle\Domain\Model\TranslationCollection;
use Phpro\SuluTranslationsBundle\Domain\Query\SearchCriteria;

interface TranslationRepository
{
    public function findById(int $id): Translation;

    public function findByCriteria(SearchCriteria $criteria): TranslationCollection;

    public function countByCriteria(SearchCriteria $criteria): int;

    public function findAllByLocaleDomain(string $locale, string $domain): TranslationCollection;

    public function findByKeyLocaleDomain(string $key, string $locale, string $domain): ?Translation;

    public function create(Translation $translation): void;

    public function update(Translation $translation): void;

    public function deleteByKeyLocaleDomain(string $key, string $locale, string $domain): void;

    public function deleteById(int $id): void;
}
