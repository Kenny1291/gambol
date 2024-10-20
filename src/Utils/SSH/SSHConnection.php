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
        $privateKeyPassword = $configuration->ssh[CONFIG_KEYS["ssh"]["children"]["privateKeyPassword"]];
        $privateKeyPath = $configuration->ssh[CONFIG_KEYS["ssh"]["children"]["privateKeyPath"]];
        $server = $configuration->ssh[CONFIG_KEYS["ssh"]["children"]["server"]];
        $username = $configuration->ssh[CONFIG_KEYS["ssh"]["children"]["username"]];
        $privateKey = file_get_contents($privateKeyPath);
        if (!$privateKey) {
            //TODO: Exit or throw
            exit(ExitStatus::FAILURE->value);
        }
        if ($privateKeyPassword) {
            $key = PublicKeyLoader::load($privateKey, $privateKeyPassword);
        } else {
            $key = PublicKeyLoader::load($privateKey);
        }
        $this->ssh = new SSH2($server);
        if (!$this->ssh->login($username, $key)) { //@phpstan-ignore argument.type
            //TODO: create exception
        }
    }

    public static function getInstance(): SSHConnection {
        if (is_null(self::$instance)) {
            self::$instance = new SSHConnection();
        }
        return self::$instance;
    }
}