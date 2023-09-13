<?php

if(!defined('KUKURUZA')) die('Hacking attempt!');

$k = [
    'main' => json_encode([
        "one_time" => false,
        "buttons" =>[
            [
                [ //1 кнопка
                    "color" => "primary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Обо мне"
                    ],
                ],
                [ //1 кнопка
                    "color" => "primary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"2\"}",
                        "label" => "Обо мне"
                    ],
                ],
            ],
            [
                [ //Остановить сессию
                    "color" => "negative",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"3\"}",
                        "label" => "Сбросить сессию"
                    ],
                ],
            ],
        ],
    ]),
    'auth_inl' => json_encode([
        "inline" => true,
        "buttons" => [
            [
                [ //Авторизоваться под инлайн
                    "color" => "primary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Авторизация"
                    ],
                ]
            ],
        ],
    ]),
    'auth_btm' => json_encode([
        "one_time" => false,
        "buttons" => [
            [
                [ //Авторизоваться под инп
                    "color" => "primary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Авторизация"
                    ],
                ],
            ],
        ],
    ]),
    'clear' => json_encode([
        "one_time" => true,
        "buttons" => [],
    ]),
];