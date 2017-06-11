<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = ['username'];

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Constructor
     */
    public function __construct(...$args)
    {
        parent::__construct(...$args);

        if (empty($this->type)) {
            $this->type = 'user';
        }
    }


    public function isSuperAdmin()
    {
        return $this->type == 'super';
    }
}
