<?php

declare (strict_types=1);

namespace Gambol\Utils;

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
            //TODO: throw exceptions on failure
            fclose($pipes[0]);
            $this->stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $this->stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            $this->exitCode = proc_close($process);
        }
    }

    public function getExitCode(): int {
        if (is_null($this->exitCode)) {
            //throw illegalstate exception
        }
        return $this->exitCode;
    }

    public function getOut(): string {
        //TODO: handle errors
        return $this->stdout;
    }

    public function getErr(): string {
        //TODO: handle errors
        return $this->stderr;
    }
 }