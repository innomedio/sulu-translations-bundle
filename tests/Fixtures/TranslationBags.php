<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Fixtures;

use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\TranslatorBag;
use Symfony\Component\Translation\TranslatorBagInterface;

final class TranslationBags
{
    public static function simple(): TranslatorBagInterface
    {
        $bag = new TranslatorBag();
        $bag->addCatalogue(
            new MessageCatalogue(
                'en',
                [
                    'messages' => [
                        'app.foo' => 'Foo',
                    ],
                ]
            )
        );

        return $bag;
    }
}
