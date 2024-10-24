<?php

declare (strict_types=1);

namespace Gambol\Commands\InitCommand;

use Gambol\Commands\Command;
use const Gambol\CONFIG_KEYS;
use Gambol\Commands\ExitStatus;
use const Gambol\CONFIG_FILE_PATH;
use function Gambol\isPhpBuiltForWindows;

final class InitCommand extends Command {
    protected static string $name = 'init';
    protected static string $description = 'Creates config file gambol.json';

    #[\Override]
    protected function execute(): ExitStatus {
        $gambolArr = [
            CONFIG_KEYS["serviceName"] => "myApp",
            CONFIG_KEYS["imageName"] => "myUser/myApp",
            CONFIG_KEYS["ssh"]["key"] => [
                CONFIG_KEYS["ssh"]["children"]["server"] => "domain.example",
                CONFIG_KEYS["ssh"]["children"]["username"] => "root",
                CONFIG_KEYS["ssh"]["children"]["privateKeyPath"] => isPhpBuiltForWindows() ? "C:\\Users\\user\\.ssh\\id_rsa" : "/home/user/.ssh/id_rsa",
                CONFIG_KEYS["ssh"]["children"]["privateKeyPassword"] => "GAMBOL_SECRET_PRIVATE_KEY_PASSWORD"
            ],
            CONFIG_KEYS["tls"]["key"] => [
                CONFIG_KEYS["tls"]["children"]["enable"] => "true",
                CONFIG_KEYS["tls"]["children"]["host"] => "app.example"
            ],
            CONFIG_KEYS["dockerRegistry"]["key"] => [
                CONFIG_KEYS["dockerRegistry"]["children"]["server"] => "hub.docker.com",
                CONFIG_KEYS["dockerRegistry"]["children"]["username"] => "myUsername",
                CONFIG_KEYS["dockerRegistry"]["children"]["password"] => "GAMBOL_SECRET_REGISTRY_PASSWORD"
            ]
        ];

        $gambolJson = json_encode($gambolArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents(CONFIG_FILE_PATH, $gambolJson);

        //TODO: check if .env exist, if it does not write a new one else
        //ask the user if he want us to append to his existing .env file

        return ExitStatus::SUCCESS;
    }
}