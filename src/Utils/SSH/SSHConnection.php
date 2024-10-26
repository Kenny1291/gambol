<?php

declare (strict_types=1);

namespace Gambol\Utils\SSH;

use phpseclib3\Net\SSH2;
use Gambol\Traits\Singleton;
use const Gambol\CONFIG_KEYS;
use Gambol\Commands\ExitStatus;
use phpseclib3\Crypt\PublicKeyLoader;
use Gambol\Utils\Configuration\Configuration;

final class SSHConnection {
    use Singleton;

    public SSH2 $ssh;

    private function __construct() {
        $configuration = Configuration::getInstance();
        //TODO: handle NECESSARY missing config values
        $privateKeyPassword = $configuration->ssh[CONFIG_KEYS["ssh"]["children"]["privateKeyPassword"]] ?? false;
        $privateKeyPath = $configuration->ssh[CONFIG_KEYS["ssh"]["children"]["privateKeyPath"]] ?? false;
        $server = $configuration->ssh[CONFIG_KEYS["ssh"]["children"]["server"]] ?? false;
        $username = $configuration->ssh[CONFIG_KEYS["ssh"]["children"]["username"]] ?? false;
        $privateKey = "";
        if ($privateKeyPath !== false) {
            $privateKey = file_get_contents($privateKeyPath);
            if (!is_string($privateKey)) {
                //TODO: Exit or throw
                exit(ExitStatus::FAILURE->value);
            }
        }
        if ($privateKeyPassword !== false) {
            $key = PublicKeyLoader::load($privateKey, $privateKeyPassword);
        } else {
            $key = PublicKeyLoader::load($privateKey);
        }
        $this->ssh = new SSH2($server);
        //TODO: check this error
        // @phpstan-ignore-next-line
        if (!$this->ssh->login($username, $key)) {
            //TODO: create exception
        }
    }
}