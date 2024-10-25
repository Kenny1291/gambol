<?php

declare (strict_types=1);

namespace Gambol\Utils;

use Gambol\Commands\ExitStatus;

final class SystemCommand {
    private const array DESCRIPTOR_SPEC = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"],
    ];
    private ?int $exitCode = null;
    private ?string $stdout = null;
    private ?string $stderr = null;

    public function __construct(string $command) {
        $process = proc_open($command, self::DESCRIPTOR_SPEC, $pipes);
        if (is_resource($process)) {
            fclose($pipes[0]);
            $stdOutOutput = stream_get_contents($pipes[1]);
            $this->stdout = $stdOutOutput !== false ? $stdOutOutput : null;
            fclose($pipes[1]);
            $stdErrOutput = stream_get_contents($pipes[2]);
            $this->stderr = $stdErrOutput !== false ? $stdErrOutput : null;
            fclose($pipes[2]);
            $this->exitCode = proc_close($process);
        }
    }

    public function getExitCode(): int {
        if (is_null($this->exitCode)) {
            //TODO: Exit or throw. It should not be possible to be null at this point.
            exit(ExitStatus::FAILURE->value);
        }
        return $this->exitCode;
    }

    public function getOut(): string {
        if (is_null($this->stdout)) {
            //TODO: Exit or throw
            exit(ExitStatus::FAILURE->value);
        }
        return $this->stdout;
    }

    public function getErr(): string {
        if (is_null($this->stderr)) {
            //TODO: Exit or throw
            exit(ExitStatus::FAILURE->value);
        }
        return $this->stderr;
    }
 }