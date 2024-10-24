<?php 

declare (strict_types=1);

namespace Gambol\Commands;

use function Gambol\anyInArray;
use Gambol\Utils\Configuration\WithConfig;
use Gambol\Utils\Configuration\Configuration;

abstract class Command {
    protected static string $name;
    protected static string $description;
    protected static ?string $_argument = null;
    protected static ?string $argument_description = null;
    /** @var array<int, string> */
    protected static array $_options = [];
    protected ?string $argument = null;
    /** @var array<int, string>  */
    protected array $options = [];

    /**
     * @param array<int, string> $args
     */
    public function __construct(array $args) {
        if (anyInArray(["--help", "-h", "help"], $args)) {
            fwrite(STDERR, self::getHelpMessage());
            exit(ExitStatus::FAILURE->value);
        }

        $reflection = new \ReflectionClass($this);
        if (count($reflection->getAttributes(WithConfig::class)) === 1) {
            Configuration::getInstance()->load();
        }

        $this->processArgs($args);
        $this->validateOptions();
    }

    /**
     * @param array<int, string> $args
     */
    private function processArgs(array $args): void {
        foreach ($args as $arg) {
            if (str_starts_with($arg, '-')) {
                if (count(static::$_options) === 0) {
                    echo "'gambol " . static::$name . "' does not support flags. See 'gambol " . static::$name . " --help'";
                    exit(ExitStatus::FAILURE->value);
                }
                $this->options[] = $arg;
            } else {
                if (!is_null($this->argument)) {
                    if (is_null(static::$_argument)) {
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

    //TODO: Handle: Flags are valid but too many or incompatible
    private function validateOptions(): void {
        if (count(static::$_options) > 0) {
            $invalidOptions = self::validate($this->options, static::$_options);
            if (count($invalidOptions) > 0) {
                $message = "The flag";
                $invalidOptionsLength = count($invalidOptions);
                if ($invalidOptionsLength > 1) {
                    $message .= 's: ';
                    foreach ($invalidOptions as $i => $invalidArgumentOrOption) {
                        $message .= $invalidArgumentOrOption . ($invalidOptionsLength - 1 === $i ? '' : ', ');
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

    /**
     * @param array<int|string, string> $toValidate
     * @param array<int|string, string> $reference
     * @return array<int|string, string>
     */
    private static function validate(array $toValidate, array $reference): array {
        $invalidElements = [];
        foreach ($toValidate as $element) {
            if (!in_array($element, $reference, true)) {
                $invalidElements[] = $element;
            }
        }
        return $invalidElements;
    }

    private static function getHelpMessage(): string {
        $message = static::$description . "\n\n";
        $message .= "USAGE\n  gambol " . static::$name;
        if (!is_null(static::$_argument)) {
            $message .= "\nARGUMENT\n   " . static::$_argument . ":  " . static::$argument_description;
        }
        if (count(static::$_options) > 0) {
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