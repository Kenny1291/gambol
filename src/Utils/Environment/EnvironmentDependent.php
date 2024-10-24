<?php

namespace Gambol\Utils\Environment;

trait EnvironmentDependent {
        private static function isLocalEnv(): bool {
            $callerInfo = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[3];
            if (!isset($callerInfo['class'])) {
                throw new \RuntimeException(); //TODO: maybe, idk
            }
            return $callerInfo['class'] === 'Gambol\Utils\Environment\Environment' && $callerInfo['function'] === 'local';
        }

        private static function isRemoteEnv(): bool {
            $callerInfo = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[3];
            if (!isset($callerInfo['class'])) {
                throw new \RuntimeException(); //TODO: maybe, idk
            }
            return $callerInfo['class'] === 'Gambol\Utils\Environment\Environment' && $callerInfo['function'] === 'remote';
        }
}