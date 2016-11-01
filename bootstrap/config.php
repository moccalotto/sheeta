<?php

return [
    'mongo' => [
        'dsn' => env('MONGO_DSN', 'mongodb://localhost:27017'),
        'database' => env('MONGO_DB', 'Sheeta'),
    ],
];
