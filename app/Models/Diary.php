<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

class Diary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'datetime',
        'activity',
        'objective',
        'description',
        'state',
        'municipality',
        'place',
        'executed',
        'wingspan',
        'observation',
        'user_id',
        'institution_id'
    ];

    public function user () {
        return $this->belongsTo(User::class);
    }
}
