<?php

declare (strict_types=1);

namespace Gambol\Utils\Environment;

final class Environment {
    public static function local(callable $callback): void {
        $callback();
    }

    public static function remote(callable $callback): void {
        $callback();
    }
}