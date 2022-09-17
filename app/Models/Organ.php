<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

class Organ extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'institution_id'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function institution() {
        return $this->belongsTo(Institution::class);
    }
}
