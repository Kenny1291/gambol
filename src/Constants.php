<?php

namespace Gambol;

const CONFIG_FILE_PATH = 'gambol.json';
const CONFIG_KEYS = [
    "serviceName" => "serviceName",
    "imageName" => "imageName",
    "server" => "server",
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