<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Consignacion extends Model implements AuditableContract
{
    use Auditable, HasFactory, HasUlids, SoftDeletes;

    protected $table = 'consignaciones';

    protected $fillable = [
        'persona_id',
        'valor_consignado',
        'fecha_consignacion',
        'observacion',
        'comprobante_path',
        'comprobante_tipo',
        'tasa_aplicada',
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
            'tasa_aplicada' => 'decimal:2',
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
