<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Export;

use App\Kernel;
use Phpro\SuluTranslationsBundle\Domain\Action\ExportAction;
use Phpro\SuluTranslationsBundle\Domain\Exception\ExportFailedException;
use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\DatabaseProviderFactory;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

final readonly class CliExportAction implements ExportAction
{
    public function __construct(
        private string $environment,
        private bool $debug,
        private string $exportFormat,
    ) {
    }

    public function __invoke(): string
    {
        try {
            $this->executeCommand(new ArrayInput([
                'command' => 'translation:pull',
                'provider' => DatabaseProviderFactory::PROVIDER_NAME,
                '--format' => $this->exportFormat,
                '--force' => true,
                '--no-interaction' => true,
            ]));

            foreach (['admin', 'website'] as $context) {
                $this->executeCommand(new ArrayInput([
                    'command' => 'cache:clear',
                ]), $context);
            }

            $this->executeCommand(new ArrayInput([
                'command' => 'fos:httpcache:clear',
            ]));

            return 'Alle vertalingen zijn aangepast';
        } catch (\Throwable $exception) {
            throw ExportFailedException::create($exception);
        }
    }

    private function executeCommand(ArrayInput $arrayInput, string $context = 'website'): void
    {
        $kernel = new Kernel(
            $this->environment,
            $this->debug,
            $context
        );

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $pullOutput = new BufferedOutput();

        $pullInput = $arrayInput;

        $exitCode = $application->run($pullInput, $pullOutput);

        if ($exitCode !== 0) {
            throw new \RuntimeException($pullOutput->fetch());
        }
    }
}
