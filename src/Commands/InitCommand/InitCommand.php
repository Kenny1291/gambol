<?php

declare (strict_types=1);

namespace Gambol\Commands\InitCommand;

use Gambol\Commands\Command;
use Gambol\Commands\ExitStatus;
use const Gambol\CONFIG_KEYS;

final class InitCommand extends Command {
    protected static string $name = 'init';
    protected static string $description = 'Creates config file gambol.json';

    protected function execute(): ExitStatus {
        //$output = shell_exec('sh /script/test.sh 2>&1');
        //echo $output;

        $gambolArr = [
            CONFIG_KEYS["serviceName"] => "myApp",
            CONFIG_KEYS["imageName"] => "myUser/myApp",
            CONFIG_KEYS["server"] => "domain.example",
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
        file_put_contents('gambol.json', $gambolJson);

        return ExitStatus::SUCCESS;
    }
}