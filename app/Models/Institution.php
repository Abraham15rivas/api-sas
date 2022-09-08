<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

class Institution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "rif",
        "latitude",
        "length"
    ];

    public function users () {
        return $this->hasMany(User::class);
    }

}
