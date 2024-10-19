<?php

declare(strict_types=1);

namespace Gambol;

function anyInArray(array $needles, array $haystack): bool {
    foreach ($needles as $needle) {
        if (in_array($needle, $haystack)) {
            return true;
        }
    }
    return false;
}

function isPhpBuiltForWindows(): bool {
    return stripos(PHP_OS, "WIN") === 0;
}
