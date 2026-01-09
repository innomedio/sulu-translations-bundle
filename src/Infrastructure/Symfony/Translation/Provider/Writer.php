<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider;

use Phpro\SuluTranslationsBundle\Domain\Command\WriteCommand;
use Phpro\SuluTranslationsBundle\Domain\Command\WriteHandler;
use Symfony\Component\Translation\TranslatorBagInterface;

/**
 * @psalm-suppress ClassMustBeFinal - Mocked in tests
 */
class Writer
{
    public function __construct(
        private readonly WriteHandler $handler,
    ) {
    }

    public function execute(TranslatorBagInterface $translatorBag): void
    {
        foreach ($translatorBag->getCatalogues() as $catalogue) {
            $locale = $catalogue->getLocale();
            /**
             * @var string $domain
             * @var array<string, string> $messagesMap
             */
            foreach ($catalogue->all() as $domain => $messagesMap) {
                foreach ($messagesMap as $translationKey => $translationMessage) {
                    ($this->handler)(new WriteCommand($translationKey, $locale, $domain, $translationMessage));
                }
            }
        }
    }
}
