<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Persona extends Model implements AuditableContract
{
    use Auditable, HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'nombre_completo',
        'cedula',
        'correo_electronico',
        'numero_telefono',
        'direccion',
        'nombre_codeudor',
        'created_by',
    ];

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function consignaciones()
    {
        return $this->hasMany(Consignacion::class);
    }
}
