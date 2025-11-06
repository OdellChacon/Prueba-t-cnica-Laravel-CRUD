<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    // columnas que se pueden asignar en mass-assignment
    protected $fillable = [
        'name',
        'email',
        'phone',
        // agregar otros campos si aplica
    ];

    // opcional: asegurar que deleted_at se trata como fecha
    protected $dates = ['deleted_at'];

    public function services()
    {
        return $this->hasMany(\App\Models\Service::class);
    }
}
