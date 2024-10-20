<?php

declare (strict_types=1);

namespace Gambol;

use Gambol\Commands\DeployCommand\DeployCommand;
use Gambol\Commands\ExitStatus;
use Gambol\Commands\HelpCommand\HelpCommand;
use Gambol\Commands\InitCommand\InitCommand;
use Gambol\Commands\SecureCommand\SecureCommand;
use Gambol\Commands\VersionCommand\VersionCommand;

final class Handler {
    /** @var array<string, string>  */
    private static array $commands;

    private static function init(): void {
        self::$commands = [
            InitCommand::getName() => InitCommand::class,
            HelpCommand::getName() => HelpCommand::class,
            HelpCommand::getAlias1() => HelpCommand::class,
            HelpCommand::getAlias2() => HelpCommand::class,
            VersionCommand::getName() => VersionCommand::class,
            VersionCommand::getAlias() => VersionCommand::class,
            DeployCommand::getName() => DeployCommand::class,
            SecureCommand::getName() => SecureCommand::class
        ];
    }

    /**
     * @param array<int, string> $argv
     */
    public static function run(array $argv): void {
        self::init();

        array_shift($argv);
        $name = $argv[0] ?? HelpCommand::getName();
        array_shift($argv);

        if (array_key_exists($name, self::$commands)) {
            $FQCN = self::$commands[$name];

            /** @var Commands\Command $command */
            $command = new $FQCN($argv);
            $command->run();
        } else {
            fwrite(STDERR, "'" . $name . "' is not a gambol command. See 'gambol --help'");
            exit(ExitStatus::FAILURE->value);
        }
    }
}