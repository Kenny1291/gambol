<?php

namespace Gambol\Commands\SecureCommand;

use Gambol\Commands\Command;
use Gambol\Commands\ExitStatus;

final class SecureCommand extends Command {
    protected static string $name = 'secure';
    protected static string $description = 'Secure the server';

    protected function execute(): ExitStatus {
        // TODO: Implement execute() method.
    }
}