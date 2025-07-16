<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    /** @use HasFactory<\Database\Factories\BoardFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'name',
        'color',
        'icon',
        'slug',
        'description',
        'background_image',
        'is_favorite',
        'is_public',
        'is_archived',
        'is_template',
        'is_read_only',
        'is_collaborative',
        'is_private',
        'is_locked',
        'is_pinned',
        'is_hidden',
        'is_archived_by_user',
        'is_shared',
        'is_synced',
        'is_trashed',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function columns()
    {
        return $this->hasMany(Column::class);
    }

    public function cards()
    {
        return $this->hasManyThrough(Card::class, Column::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'board_members')->withPivot('role')->withTimestamps();
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
