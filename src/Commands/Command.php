<?php 

declare (strict_types=1);

namespace Gambol\Commands;

use Gambol\Commands\Traits\WithConfig;
use function Gambol\anyInArray;

abstract class Command {
    protected static string $name;
    protected static string $description;
    protected static ?string $_argument = null;
    protected static ?string $argument_description = null;
    protected static ?array $_options = null;
    protected ?string $argument = null;
    protected ?array $options = null;

    public function __construct(array $args) {
        if ($args && anyInArray(["--help", "-h", "help"], $args)) {
            fwrite(STDERR, static::getHelpMessage());
            exit(ExitStatus::FAILURE->value);
        }
        if (in_array(WithConfig::class, class_uses($this))) {
            $this->loadConfig();
        }
        $this->processArgs($args);
        $this->validateOptions();
    }

    private function processArgs(array $args): void {
        foreach ($args as $arg) {
            if (str_starts_with($arg, '-')) {
                if (!static::$_options) {
                    echo "'gambol " . static::$name . "' does not support flags. See 'gambol " . static::$name . " --help'";
                    exit(ExitStatus::FAILURE->value);
                }
                $this->options[] = $arg;
            } else {
                if (!$this->argument) {
                    if (!static::$_argument) {
                        echo "'gambol " . static::$name . "' does not support argument. See 'gambol " . static::$name . " --help'";
                        exit(ExitStatus::FAILURE->value);
                    }
                    $this->argument = $arg;
                } else {
                    $message = "More than one argument provided. Gambol commands support only a single argument.";
                    $message .= " See 'gambol '" . static::$name . " --help'";
                    echo $message;
                    exit(ExitStatus::FAILURE->value);
                }
            }
        }
    }

    //TODO: Flags are valid but too many or incompatible
    private function validateOptions(): void {
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
                exit(ExitStatus::FAILURE->value);
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

    private static function getHelpMessage(): string {
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

    final public function run(): never {
        //TODO: handle CTRL + C
        exit($this->execute()->value);
    }

    final public static function getName(): string {
        return static::$name;
    }
}