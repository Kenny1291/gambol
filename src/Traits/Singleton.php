<?php

declare(strict_types=1);

namespace Gambol\Traits;

trait Singleton {
    private static ?self $instance = null;

    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}