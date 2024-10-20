<?php

declare (strict_types=1);

namespace Gambol\Utils\Configuration\Dotenv;

use Gambol\Commands\ExitStatus;
use const Gambol\GAMBOL_SECRETS;

final class Dotenv {
    private static ?Dotenv $instance = null;
    public ?string $privateKeyPassword = null;
    public ?string $registryPassword = null;

    private function __construct() {
        $dotenvFile = file_get_contents(__DIR__.'/../../../../.example.env');
        if (!$dotenvFile) {
            //TODO: echo
            exit(ExitStatus::FAILURE->value);
        }
        $this->parse($dotenvFile);
    }

    private function parse(string $dotenvFile): void {
        $secretNames = array_values(GAMBOL_SECRETS);
        $lines = explode(PHP_EOL, $dotenvFile);
        foreach ($lines as $line) {
            $pair = explode("=", $line);
            if (count($pair) !== 2) {
                continue;
            }
            [$key, $value] = $pair;
            if (array_key_exists($key, $secretNames)) {
                $parsedValue = self::parseValue($value);
                $field = self::fromGambolSecretToField($key);
                $this->{$field} = $parsedValue;
            }
        }
    }

    public static function fromGambolSecretToField(string $secretKey): string {
        $secretKey = strtolower($secretKey);
        $exploded = explode("_", $secretKey);
        array_shift($exploded);
        array_shift($exploded);
        for ($i = 1; $i < count($exploded); $i++) {
            $exploded[$i] = ucfirst($exploded[$i]);
        }
        return implode($exploded);
    }

    private static function parseValue(string $value): string {
        $parsedValue = "";
        if (!empty($value) && $value[0] === "=") {
            $parsedValue = substr_replace($value, "", 0, 1);
        }
        $lastIndex = strlen($value) - 1;
        if (!empty($parsedValue) && $parsedValue[$lastIndex] === "=") {
            $parsedValue = substr_replace($parsedValue, "", $lastIndex, 1);
        }
        return $parsedValue;
    }

    public static function getInstance(): Dotenv {
        if (is_null(self::$instance)) {
            self::$instance = new Dotenv();
        }
        return self::$instance;
    }
}