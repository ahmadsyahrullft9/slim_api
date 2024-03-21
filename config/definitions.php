<?php

use App\Database;
use App\Auth;

return [
    Database::class => function () {
        return new Database(
            $_ENV['DB_HOST'],
            $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
    },
    Auth::class=>function(){
        return new Auth(
            $_ENV['JWT_ALGORITHM'],
            $_ENV['JWT_SECRET_KEY']
        );
    }
];
