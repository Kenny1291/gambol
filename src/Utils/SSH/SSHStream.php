<?php

declare (strict_types=1);

namespace Gambol\Utils\SSH;

use phpseclib3\Net\SSH2;

final class SSHStream {
    private SSH2 $ssh;

    private function __construct(string $command) {
        $this->ssh = SSHConnection::getInstance()->ssh;
        $this->write($command);
    }

    public function write(string $command): void {
        $this->ssh->write($command);
    }

    public function readLine(): \Generator {
        while ($line = $this->ssh->read()) {
            yield $line;
        }
    }
}