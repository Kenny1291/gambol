<?php

namespace Gambol;

use Gambol\Commands\InitCommand\InitCommand;

final class Handler {
    private static array $commands;

    private static function init(): void {
        self::$commands = [
            InitCommand::class => InitCommand::getName(),
        ];
    }

    public static function run(array $argv): void {
        self::init();

        array_shift($argv);
        $name = $argv[0];
        array_shift($argv);
        if (in_array($name, self::$commands)) {
            $FQCN = array_search($name, self::$commands);
            $command = new $FQCN($argv);
            $command->run();

        } else {
            echo "'" . $name . "' is not a Gambol command. See 'gambol --help'";
            exit();
        }
    }
}