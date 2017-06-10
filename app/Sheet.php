<?php

namespace App;

use DB;
use Gate;
use Moccalotto\Valit\Facades\Ensure;
use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tables' => 'array',
        'visible_headline' => 'boolean',
        'allow_clone' => 'boolean',
        'allow_view' => 'boolean',
        'version' => 'integer',
        'user_id' => 'integer',
        'original_id' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['user_id'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * @var array
     */
    protected $appends = ['slug', 'editable_by_current_user'];

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    public function getSlugAttribute()
    {
        return str($this->headline)->slug()->string();
    }

    public function getEditableByCurrentUserAttribute()
    {
        return Gate::allows('update', $this);
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
                'allow_clone' => $this->allow_clone,
                'allow_view' => $this->allow_view,
            ];

            $this->clone_count++;
            $this->save();

            return Sheet::forceCreate($attributes);
        });
    }

    public function applyPatch(array $path, $value)
    {
        $first = array_shift($path);

        Ensure::that($first)->isString()->isOneOf(['headline', 'allow_clone', 'allow_view', 'tables']);

        if ($first === 'headline') {
            Ensure::that($path)->isEmptyArray();
            Ensure::that($value)->isString()->longerThan(0);
            $this->version += $this->headline != $value;
            $this->headline = $value;
            return;
        }

        if ($first === 'allow_clone') {
            Ensure::that($path)->isEmptyArray();
            Ensure::that($value)->isBoolean(0);
            $this->version += $this->allow_clone != $value;
            $this->allow_clone = $value;
            return;
        }
        if ($first === 'allow_view') {
            Ensure::that($path)->isEmptyArray();
            Ensure::that($value)->isBoolean(0);
            $this->version += $this->allow_view != $value;
            $this->allow_view = $value;
            return;
        }

        $tables = $this->tables;
        $currentItem = &$tables;
        $prev = 'tables';
        $last = array_pop($path);

        foreach ($path as $key) {
            Ensure::that($currentItem)->as($prev)->hasKey($key);
            $currentItem = &$currentItem[$key];
            $prev = $key;
        }

        $currentItem[$last] = $value;

        if ($tables !== $this->tables) {
            $this->version++;
            $this->tables = $tables;
        }
    }
}
