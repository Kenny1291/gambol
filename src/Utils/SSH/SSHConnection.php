<?php

declare (strict_types=1);

namespace Gambol\Utils\SSH;

use Gambol\Commands\ExitStatus;
use Gambol\Utils\Configuration\Configuration;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;
use const Gambol\CONFIG_KEYS;

final class SSHConnection {
    private static ?SSHConnection $instance = null;
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

    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}