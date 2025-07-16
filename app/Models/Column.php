<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'board_id',
        'name',
        'color',
        'position',
        'is_locked',
        'is_hidden',
        'slug',
        'description',
        'is_archived',
        'is_template',
        'is_read_only',
        'is_collaborative',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class)->orderBy('position');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
