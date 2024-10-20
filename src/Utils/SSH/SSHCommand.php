<?php

declare (strict_types=1);

namespace Gambol\Utils\SSH;

use Gambol\Commands\ExitStatus;

final class SSHCommand {
    private ?string $stdout = null;
    private ?string $stderr = null;
    private int $exitCode;

    public function __construct(string $command) {
        $this->execute($command);
    }

    private function execute(string $command): void {
        //TODO: handle errors
        $ssh = SSHConnection::getInstance()->ssh;
        $ssh->enableQuietMode();
        $execOutput = $ssh->exec($command);
        if (!is_string($execOutput)) {
            //TODO: Exit or throw
            exit(ExitStatus::FAILURE->value);
        }
        $this->stdout = $execOutput;
        $exitStatusOutput = $ssh->getExitStatus();
        if (!is_int($exitStatusOutput)) {
            //TODO: Exit or throw
            exit(ExitStatus::FAILURE->value);
        }
        $this->exitCode = $exitStatusOutput;
        $this->stderr = $ssh->getStdError();
        $ssh->disableQuietMode();
    }

    public function getOut(): string|null {
        //TODO: handle errors
        return $this->stdout;
    }

    public function getErr(): string|null {
        //TODO: handle errors
        return $this->stderr;
    }

    public function getExitCode(): int {
        //TODO: handle errors
        return $this->exitCode;
    }
}