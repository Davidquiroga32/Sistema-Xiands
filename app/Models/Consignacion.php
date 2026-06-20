<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consignacion extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'consignaciones';

    protected $fillable = [
        'persona_id',
        'valor_consignado',
        'fecha_consignacion',
        'observacion',
        'comprobante_path',
        'comprobante_tipo',
        'interes_aplicado',
        'total_con_interes',
        'created_by',
        'interes_aplicado_by',
        'interes_aplicado_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_consignacion' => 'date',
            'interes_aplicado' => 'decimal:2',
            'total_con_interes' => 'decimal:2',
            'valor_consignado' => 'decimal:2',
        ];
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function interesAplicadoPor()
    {
        return $this->belongsTo(User::class, 'interes_aplicado_by');
    }
}
