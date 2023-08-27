<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model {
    use HasFactory;
    use HasTimestamps;

    protected $fillable = [
        'name',
        'description',
        'start',
        'end',
        'owner_id',
    ];
    protected $casts = [
        'id' => 'integer',
        'start' => 'datetime',
        'end' => 'datetime',
        'owner_id' => 'integer',
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
    public function members(): HasMany {
        return $this->hasMany(GroupMember::class, 'group_id');
    }
}
