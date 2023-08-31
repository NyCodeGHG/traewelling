<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model {
    use HasFactory;
    use HasTimestamps;

    protected $fillable = [
        'name',
        'description',
        'inactivity_hours',
        'owner_id',
        'active',
    ];
    protected $casts = [
        'id' => 'integer',
        'inactivity_hours' => 'integer',
        'owner_id' => 'integer',
        'active' => 'boolean',
        'last_activity' => 'datetime',
    ];

    /**
     * The user which created the group.
     */
    public function owner(): HasOne {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    /**
     * The members of the group.
     */
    public function members(): BelongsToMany {
        return $this->belongsToMany(User::class, 'group_members');
    }
}
