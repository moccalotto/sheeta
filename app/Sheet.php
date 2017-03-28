<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'tables' => 'collection',
        'allow_copy' => 'boolean',
        'version' => 'integer',
    ];
}
