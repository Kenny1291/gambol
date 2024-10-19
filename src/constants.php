<?php

declare (strict_types=1);

namespace Gambol;

const CONFIG_FILE_PATH = 'gambol.json';

const CONFIG_KEYS = [
    "serviceName" => "serviceName",
    "imageName" => "imageName",
    "ssh" => [
        "key" => "ssh",
        "children" => [
            "server" => "server",
            "username" => "username",
            "privateKeyPath" => "privateKeyPath",
            "privateKeyPassword" => "privateKeyPassword"
        ]
    ],
    "tls" => [
        "key" => "tls",
        "children" => [
            "enable" => "enable",
            "host" => "host"
        ]
    ],
    "dockerRegistry" => [
        "key" => "dockerRegistry",
        "children" => [
            "server" => "server",
            "username" => "username",
            "password" => "password"
        ]
    ]
];

const GAMBOL_SECRETS = [
    "GAMBOL_SECRET_PRIVATE_KEY_PASSWORD" => "GAMBOL_SECRET_PRIVATE_KEY_PASSWORD",
    "GAMBOL_SECRET_REGISTRY_PASSWORD" => "GAMBOL_SECRET_REGISTRY_PASSWORD"
];