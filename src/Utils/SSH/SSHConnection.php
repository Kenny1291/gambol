<?php

declare (strict_types=1);

namespace Gambol\Utils\SSH;

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
        if ($privateKeyPassword) {
            $key = PublicKeyLoader::load(file_get_contents($privateKeyPath), $privateKeyPassword);
        } else {
            $key = PublicKeyLoader::load(file_get_contents($privateKeyPath));
        }
        $this->ssh = new SSH2($server);
        if (!$this->ssh->login($username, $key)) {
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