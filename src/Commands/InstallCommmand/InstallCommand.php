<?php

namespace Gambol\Commands\InstallCommmand;

use Gambol\Commands\Command;
use Gambol\Commands\ExitStatus;

final class InstallCommand extends Command {
    protected static string $name = 'install';
    protected static string $description = 'Install gambol on the server';

    protected function execute(): ExitStatus {
        // TODO: Implement execute() method.
    }
}