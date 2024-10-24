<?php

declare (strict_types=1);

namespace Gambol\Commands\DeployCommand;

use Gambol\Commands\Command;
use Gambol\Commands\ExitStatus;
use Gambol\Tools\Docker;
use Gambol\Utils\Configuration\WithConfig;
use Gambol\Utils\Environment\Environment;

#[WithConfig]
final class DeployCommand extends Command {
    protected static string $name = 'deploy';
    protected static string $description = 'Deploy the application';

    #[\Override]
    protected function execute(): ExitStatus {
        Environment::local(static function () {
            $dockerInstalled = Docker::isInstalled();
            if ($dockerInstalled) {
                echo "yes";
            } else {
                echo "no";
            }
        });
        return ExitStatus::SUCCESS;
    }
}