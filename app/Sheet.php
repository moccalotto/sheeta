<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Sheet extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'tables' => 'array',
        'allow_clone' => 'boolean',
        'version' => 'integer',
        'user_id' => 'integer',
        'original_id' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'user_id', 'original_id'];

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

    public function canBeClonedBy(User $user)
    {
        if ($this->allow_clone) {
            return true;
        }

        if ($this->ownedBy($user)) {
            return true;
        }

        return false;
    }

    public function ownedBy(User $user)
    {
        return $this->user_id = $user->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function originalSheet()
    {
        return $this->belongsTo(Sheet::class, 'original_id');
    }

    public function createClone(User $user)
    {
        return DB::transaction(function () use ($user) {
            $attributes = [
                'headline' => $this->headline,
                'user_id' => $user->id,
                'original_id' => $this->id,
                'tables' => $this->tables,
                'allow_clone' => $this->allow_clone,
                'clone_level' => $this->clone_level + 1,
                'version' => 1,
            ];

            $this->clone_count++;
            $this->save();

            return Sheet::forceCreate($attributes);
        });
    }

}
