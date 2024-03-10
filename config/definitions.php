<?php

use App\Database;

return [
    Database::class => function(){
        return new Database('localhost', 'tasks', 'root', 'password');
    }
];
