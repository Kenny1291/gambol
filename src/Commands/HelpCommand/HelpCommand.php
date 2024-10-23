<?php

namespace Gambol\Commands\HelpCommand;

use Gambol\Commands\Command;
use Gambol\Commands\ExitStatus;

final class HelpCommand extends Command {
    protected static string $name = 'help';
    protected static string $description = 'Show help';

    public function __construct() {} //If help is the subcommand we do not parse $args.

    #[\Override]
    protected function execute(): ExitStatus {
        $help = "this is the help output";

        fwrite(STDOUT, $help);
        return ExitStatus::SUCCESS;
    }

    public static function getAlias1(): string {
        return '--help';
    }

    public static function getAlias2(): string {
        return '-h';
    }
}