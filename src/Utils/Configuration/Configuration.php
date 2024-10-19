<?php

declare (strict_types=1);

namespace Gambol\Utils\Configuration;

use Gambol\Utils\Configuration\Dotenv\Dotenv;
use const Gambol\CONFIG_FILE_PATH;
use const Gambol\CONFIG_KEYS;
use const Gambol\GAMBOL_SECRETS;

final class Configuration {
    private static ?Configuration $instance = null;
    public ?string $serviceName = null;
    public ?string $imageName = null;
    public ?array $ssh = null;
    public ?array $tls = null;
    public ?array $dockerRegistry = null;

    public function load(): void {
        $gambolJson = file_get_contents(CONFIG_FILE_PATH);
        $gambolObj = json_decode($gambolJson, true);
        $this->parse($gambolObj);
    }

    private function parse(array $gambolObj): void {
        foreach ($gambolObj as $firstLevelKey => $firstLevelValue) {
            if (is_array($firstLevelValue)) {
                $this->processMultiDimensionalLevel($firstLevelKey, $firstLevelValue);
            } else {
                $this->processSingleDimensionalLevel($firstLevelKey, $firstLevelValue);
            }
        }
    }

    private function processSingleDimensionalLevel(string $firstLevelKey, string $firstLevelValue): void {
        foreach (CONFIG_KEYS as $configKey => $configValue) {
            if ($firstLevelKey === $configValue) {
                $this->replaceIfSecret($firstLevelValue);
                $this->{$firstLevelKey} = $firstLevelValue;
                break;
            }
        }
    }

    private function processMultiDimensionalLevel(string $firstLevelKey, string|array $firstLevelValue): void {
        foreach (CONFIG_KEYS as $configKey => $configValue) {
            if (is_array($configValue) && $firstLevelKey === $configValue["key"]) {
                foreach ($firstLevelValue as $secondLevelKey => $secondLevelValue) {
                    foreach ($configValue["children"] as $childKey => $childValue) {
                        if ($secondLevelKey === $childValue) {
                            $this->replaceIfSecret($secondLevelValue);
                            if (!isset($this->{$firstLevelKey})) {
                                $this->{$firstLevelKey} = [];
                            }
                            $this->{$firstLevelKey}[$secondLevelKey] = $secondLevelValue;
                            break;
                        }
                    }
                }
            }
        }
    }

    private function replaceIfSecret(string &$value): void {
        if ($this->isSecret($value)) {
            $value = $this->fetchSecret($value);
        }
    }

    private function isSecret(string $value): bool {
        if (str_starts_with($value, "GAMBOL_SECRET_")) {
            if (array_key_exists($value, GAMBOL_SECRETS)) {
                return true;
            }
        }
        return false;
    }

    private function fetchSecret(string $value): string|null {
        $field = Dotenv::fromGambolSecretToField($value);
        return Dotenv::getInstance()->{$field};
    }

    public static function getInstance(): Configuration {
        if (is_null(self::$instance)) {
            self::$instance = new Configuration();
        }
        return self::$instance;
    }
}