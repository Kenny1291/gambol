<?php

declare (strict_types=1);

namespace Gambol\Commands;

use Gambol\Commands\ExitStatus;

abstract class Command {
    protected static string $name;
    protected static string $description;
    protected static ?string $_argument = null;
    protected static ?string $argument_description = null;
    protected static ?array $_options = null;
    protected ?string $argument = null;
    protected ?array $options = null;

    final public function __construct(array $args) {
        if (in_array(WithConfig::class, class_uses($this))) {
            $this->loadConfig();
        }

        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {
                $this->options[] = $arg;
            } else {
                if (!$this->argument) {
                    if (!static::$_argument) {
                        echo "'gambol " . static::$name . "' does not support argument. See 'gambol " . static::$name . " --help'";
                        exit();
                    }
                    $this->argument = $arg;
                } else {
                    $message = "More than one argument provided. Gambol commands support only a single argument.";
                    $message .= " See 'gambol '" . static::$name . " --help'";
                    echo $message;
                    exit();
                }
            }
        }

        if ($this->options && in_array("--help", $this->options)) {
            echo $this->getHelpMessage();
            exit();
        }

        if ($this->options && !static::$_options) {
            echo "'gambol " . static::$name . "' does not support flags. See 'gambol " . static::$name . " --help'";
            exit();
        }

        if (static::$_options) {
            $invalidOptions = self::validate($this->options, static::$_options);

            if ($invalidOptions) {
                $message = "The flag";
                $invalidOptionsLength = count($invalidOptions);
                if ($invalidOptionsLength > 1) {
                    $message .= 's: ';
                    foreach ($invalidOptions as $i => $invalidArgumentOrOption) {
                        $message .= $invalidArgumentOrOption . $invalidOptionsLength - 1 === $i ? '' : ', ';
                    }
                    $message .= " are ";
                } else {
                    $message .= ": $invalidOptions[0] is ";
                }
                $message .= "not valid. See 'gambol " . static::$name . " --help'";

                echo $message;
                exit();
            }
        }
    }

    private static function validate(array $toValidate, array $reference): array {
        $invalidElements = [];
        foreach ($toValidate as $element) {
            if (!in_array($element, $reference)) {
                $invalidElements[] = $element;
            }
        }
        return $invalidElements;
    }

    private function getHelpMessage(): string {
        $message = static::$description . "\n\n";
        $message .= "USAGE\n  gambol " . static::$name;
        if (static::$_argument) {
            $message .= "\nARGUMENT\n   " . static::$_argument . ":  " . static::$argument_description;
        }
        if (static::$_options) {
            $message .= "\n\nFLAGS\n";
            foreach (static::$_options as $option => $description) {
                $message .= "  $option:  $description";
            }
        }
        return $message;
    }

    abstract protected function execute(): ExitStatus;

    final public function run(): ExitStatus {
        return $this->execute();
    }

    final public static function getName() {
        return static::$name;
    }
}