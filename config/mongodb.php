<?php

return [
    'db' => 'Sheeta',
    'uri' => 'mongodb://localhost:27017',
    'options' => [],
    'driverOptions' => [
        // change at your own peril!!
        'typeMap' => [
            'root' => 'array',
            'document' => 'array',
            'array' => 'array'
        ],
    ]
];
