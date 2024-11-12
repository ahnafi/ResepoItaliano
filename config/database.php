<?php

function getDatabaseConfig(): array
{
    return [
        "database" => [
            "test" => [
                "url" => "mysql:host=localhost;dbname=",
                "username" => "root",
                "password" => ""
            ],
            "prod" => [
                "url" => "mysql:host=localhost;dbname=resepo_italiano",
                "username" => "root",
                "password" => ""
            ]
        ]
    ];
}