<?php

namespace Gambol\Utils\Environment;

trait EnvironmentDependent {
    private static function isLocalEnv(): bool {
        $callerInfo = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[3];
        return ($callerInfo['class'] === 'Gambol\Utils\Environment\Environment' && $callerInfo['function'] === 'local');
    }

    private static function isRemoteEnv(): bool {
        $callerInfo = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[3];
        return ($callerInfo['class'] === 'Gambol\Utils\Environment\Environment' && $callerInfo['function'] === 'remote');
    }
}