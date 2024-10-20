<?php

declare (strict_types=1);

namespace Gambol\Tools;

use Gambol\Utils\Environment\EnvironmentDependent;
use Gambol\Utils\SSH\SSHCommand;
use Gambol\Utils\SystemCommand;

final class Docker {
    use EnvironmentDependent;

    private const string BASE_COMMAND = 'docker ';

    public static function isInstalled(): bool {
        $command = self::BASE_COMMAND.'-v';
        $isInstalled = false;
        if (self::isLocalEnv()) {
            $systemCmd = new SystemCommand($command);
            if ($systemCmd->getExitCode() === 0) {
                $isInstalled = true;
            }
        } else {
            $sshCmd = new SSHCommand($command);
            if (!is_null($sshCmd->getErr())) {
                $isInstalled = true;
            }
        }
        return $isInstalled;
    }

    public static function build(): void {
        $command = self::BASE_COMMAND.'build';
    }
}