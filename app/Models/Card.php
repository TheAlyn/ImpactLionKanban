<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'board_id',
        'column_id',
        'user_id',
        'title',
        'description',
        'position',
        'due_date',
        'priority',
        'color',
        'icon',
        'is_archived',
        'slug',
        'background_image',
        'is_template',
        'is_read_only',
        'is_collaborative',
        'is_public',
        'is_private',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function comments()
    {
        return $this->hasMany(CardComment::class);
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
