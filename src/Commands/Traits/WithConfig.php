<?php

declare (strict_types=1);

namespace Gambol\Commands\Traits;

use const Gambol\CONFIG_FILE_PATH;
use const Gambol\CONFIG_KEYS;

trait WithConfig {
    protected string $serviceName;
    protected string $imageName;
    protected array $tls;
    protected array $dockerRegistry;

    public function loadConfig(): void {
        $gambolJson = file_get_contents(CONFIG_FILE_PATH);
        $gambolObj = json_decode($gambolJson, true);
        self::validate($gambolObj);
        $this->serviceName = $gambolObj[CONFIG_KEYS['serviceName']];
        $this->imageName = $gambolObj[CONFIG_KEYS['imageName']];
        $this->tls = $gambolObj[CONFIG_KEYS['tls']['key']];
        $this->dockerRegistry = $gambolObj[CONFIG_KEYS['dockerRegistry']['key']];
    }

    private static function validate(array $gambolObj): void {
        //validate by using CONFIG_KEY values


//        if (str_starts_with($value, "GAMBOL_SECRET")) {
//            $secret = $this->loadSecret($value);
//            //replace value with secret
//        }
    }
}