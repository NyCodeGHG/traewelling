<?php

namespace App\Models;

use App\Enum\MapVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class uMap extends Model
{
    use HasFactory;

    protected $table = "umap";
    protected $fillable = ['user_id', 'map_url', 'profile_visibility'];
    protected $hidden   = ['user_id'];
    protected $casts = [
      'user_id' => 'integer',
      'map_url' => 'string',
      'profile_visibility' => MapVisibility::class,
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
