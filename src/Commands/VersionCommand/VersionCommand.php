<?php

namespace Gambol\Commands\VersionCommand;

use Gambol\Commands\Command;
use Gambol\Commands\ExitStatus;

final class VersionCommand extends Command {
    protected static string $name = 'version';
    protected static string $description = 'Show version';

    protected function execute(): ExitStatus {
        fwrite(STDOUT, "version");
        return ExitStatus::SUCCESS;
    }

    public static function getAlias(): string {
        return "-v";
    }
}