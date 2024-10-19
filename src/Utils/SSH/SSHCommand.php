<?php

declare (strict_types=1);

namespace Gambol\Utils\SSH;

final class SSHCommand {
    private string $stdout;
    private string $stderr;
    private int $exitCode;

    public function __construct(string $command) {
        $this->execute($command);
    }

    private function execute(string $command): void {
        //TODO: handle errors
        $ssh = SSHConnection::getInstance()->ssh;
        $ssh->enableQuietMode();
        $this->stdout = $ssh->exec($command);
        $this->exitCode = $ssh->getExitStatus();
        $this->stderr = $ssh->getStdError();
        $ssh->disableQuietMode();
    }

    public function getOut(): string {
        //TODO: handle errors
        return $this->stdout;
    }

    public function getErr(): string {
        //TODO: handle errors
        return $this->stderr;
    }

    public function getExitCode(): int {
        //TODO: handle errors
        return $this->exitCode;
    }
}