<?php

declare (strict_types=1);

namespace Gambol\Commands\InstallCommand;

use Gambol\Commands\Command;
use Gambol\Commands\ExitStatus;

final class InstallCommand extends Command {
    protected static string $name = 'install';
    protected static string $description = 'Install gambol on the server';
    #[\Override]
    protected function execute(): ExitStatus {
        return ExitStatus::SUCCESS;
    }
}