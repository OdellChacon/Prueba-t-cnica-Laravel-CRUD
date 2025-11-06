<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'provider_id',
        'name',
        'duration_minutes',
        'price',
    ];
    protected $dates = ['deleted_at'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
