<?php

declare (strict_types=1);

namespace Gambol\Utils\Configuration;

use Gambol\Commands\ExitStatus;
use const Gambol\CONFIG_KEYS;
use const Gambol\GAMBOL_SECRETS;
use const Gambol\CONFIG_FILE_PATH;
use Gambol\Utils\Configuration\Dotenv\Dotenv;

final class Configuration {
    private static ?Configuration $instance = null;
    public ?string $serviceName = null;
    public ?string $imageName = null;
    /** @var array<string, string> */
    public array $ssh = [];
    /** @var array<string, string> */
    public array $tls = [];
    /** @var array<string, string> */
    public array $dockerRegistry = [];

    public function load(): void {
        $gambolJson = file_get_contents(CONFIG_FILE_PATH);
        if (!is_string($gambolJson)) {
            //TODO: print something
            exit(ExitStatus::FAILURE->value);
        }
        /** @var array<string, string|array<string, string>> $gambolArr */
        $gambolArr = json_decode($gambolJson, true);
        $this->parse($gambolArr);
    }

    /**
     * @param array<string, string|array<string, string>> $gambolArr
     */
    private function parse(array $gambolArr): void {
        foreach ($gambolArr as $firstLevelKey => $firstLevelValue) {
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

    /**
     * @param array<string, string> $firstLevelValue
     */
    private function processMultiDimensionalLevel(string $firstLevelKey, array $firstLevelValue): void {
        foreach (CONFIG_KEYS as $configKey => $configValue) {
            if (is_array($configValue) && $firstLevelKey === $configValue["key"]) {
                foreach ($firstLevelValue as $secondLevelKey => $secondLevelValue) {
                    foreach ($configValue["children"] as $childKey => $childValue) {
                        if ($secondLevelKey === $childValue) {
                            $this->replaceIfSecret($secondLevelValue);
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

    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}