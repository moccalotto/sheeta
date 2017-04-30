<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'tables' => 'array',
        'allow_copy' => 'boolean',
        'version' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'version'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['api_path'];

    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getApiPathAttribute()
    {
        return "/api/sheets/{$this->id}";
    }
}
