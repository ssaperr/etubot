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
                        "label" => "Команды"
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
                [ //В начало
                    "color" => "primary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Посещаемость"
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
                [
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
    'start' => json_encode([
        "one_time" => false,
        "buttons" => [
            [
                [ //В начало
                    "color" => "secondary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Начало"
                    ],
                ],
            ],
        ],
    ]),
    'att_aa_off' => json_encode([
        "one_time" => false,
        "buttons" => [
            [
                [ //В начало
                    "color" => "primary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Доступные предметы"
                    ],
                ],
            ],
            [
                [ //В начало
                    "color" => "secondary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Автоматически отмечать на всех предметах"
                    ],
                ],
            ],
            [
                [ //В начало
                    "color" => "secondary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Начало"
                    ],
                ],
            ],
        ],
    ]),
    'att_aa_on' => json_encode([
        "one_time" => false,
        "buttons" => [
            [
                [ //В начало
                    "color" => "primary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Доступные предметы"
                    ],
                ],
            ],
            [
                [ //В начало
                    "color" => "secondary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Не отмечать автоматически на парах"
                    ],
                ],
            ],
            [
                [ //В начало
                    "color" => "secondary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Начало"
                    ],
                ],
            ],
        ],
    ]),
    'att_n_start' => json_encode([
        "one_time" => false,
        "buttons" => [
            [
                [ //В начало
                    "color" => "primary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Посещаемость"
                    ],
                ],
            ],
            [
                [ //В начало
                    "color" => "secondary",
                    "action" => [
                        "type" => "text",
                        "payload" => "{\"button\": \"1\"}",
                        "label" => "Начало"
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