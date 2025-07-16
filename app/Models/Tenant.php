<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'owner_id',
        'slug',
        'domain',
        'logo',
        'color',
        'description',
        'is_active',
        'is_verified',
        'is_premium',
        'is_archived',
    ];

    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    public function columns()
    {
        return $this->hasMany(Column::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_user')->withPivot('role')->withTimestamps();
    }

    public function teams()
{
    return $this->hasMany(Team::class);
}

}
