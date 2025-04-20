<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class User extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function positions(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
}
