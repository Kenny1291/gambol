<?php

declare (strict_types=1);

namespace Gambol\Utils\SSH;

final class SSHStream {
    private function __construct() {

    }

    public function write(string $command): void {
        $this->ssh->write($command);
    }

    public function readLine(): \Generator {
        while($line = $this->ssh->read()) {
            yield $line;
        }
    }
}