<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Export;

use Phpro\SuluTranslationsBundle\Domain\Action\ExportAction;
use Phpro\SuluTranslationsBundle\Domain\Exception\ExportFailedException;
use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\DatabaseProviderFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CliExportAction implements ExportAction
{
    /**
     * @param string $projectDir
     * @param string $exportFormat
     */
    public function __construct(
        private readonly string $projectDir,
        private readonly string $exportFormat,
    ) {
    }

    public function __invoke(): string
    {
        try {
            $pullTranslationsProcess = new Process([
                'php',
                'bin/console',
                '--no-interaction',
                'translation:pull',
                DatabaseProviderFactory::PROVIDER_NAME,
                '--format',
                $this->exportFormat,
                '--force',
            ], timeout: 180);
            $pullTranslationsProcess->setWorkingDirectory($this->projectDir);
            $pullTranslationsProcess->mustRun();

            foreach (['adminconsole', 'websiteconsole'] as $console) {
                $cacheCleanProcess = new Process([
                    'php',
                    'bin/'.$console,
                    'cache:clear',
                ], timeout: 180);
                $cacheCleanProcess->setWorkingDirectory($this->projectDir);
                $cacheCleanProcess->mustRun();
            }

            return $pullTranslationsProcess->getOutput();
        } catch (ProcessFailedException $exception) {
            throw ExportFailedException::create($exception);
        }
    }
}
