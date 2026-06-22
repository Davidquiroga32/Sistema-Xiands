<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XIANDS — Reporte de Consignaciones</title>
    <style>
        body { font-family: 'Inter', 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #111; padding-bottom: 12px; }
        .header h1 { margin: 0; font-size: 20px; letter-spacing: 4px; color: #111; }
        .header p { margin: 4px 0 0; font-size: 10px; color: #666; }
        .summary { display: flex; gap: 20px; margin-bottom: 20px; }
        .summary-item { flex: 1; background: #f5f5f5; padding: 10px 14px; border-radius: 6px; text-align: center; }
        .summary-item .val { font-size: 16px; font-weight: 700; color: #111; }
        .summary-item .lbl { font-size: 8px; text-transform: uppercase; color: #888; letter-spacing: 1px; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #111; color: #fff; padding: 8px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 8px 10px; border-bottom: 1px solid #e0e0e0; font-size: 11px; }
        tr:nth-child(even) td { background: #fafafa; }
        .amount { text-align: right; font-weight: 600; }
        .footer { margin-top: 24px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #e0e0e0; padding-top: 12px; }
        .interest { color: #4a7a4a; }
    </style>
</head>
<body>
    <div class="header">
        <h1>XIANDS</h1>
        <p>Sistema de Gestión &middot; Reporte de Consignaciones &middot; {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="val">{{ $consignaciones->count() }}</div>
            <div class="lbl">Registros</div>
        </div>
        <div class="summary-item">
            <div class="val">$ {{ number_format($totalValor, 0, ',', '.') }}</div>
            <div class="lbl">Total consignado</div>
        </div>
        <div class="summary-item">
            <div class="val" style="color:#4a7a4a;">$ {{ number_format($totalIntereses, 0, ',', '.') }}</div>
            <div class="lbl">Intereses</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Persona</th>
                <th>Cédula</th>
                <th>Valor</th>
                <th>Fecha</th>
                <th>Interés</th>
                <th>Total</th>
                <th>Tipo</th>
                <th>Registrado por</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consignaciones as $c)
                <tr>
                    <td>{{ $c->persona?->nombre_completo ?? '—' }}</td>
                    <td>{{ $c->persona?->cedula ?? '—' }}</td>
                    <td class="amount">$ {{ number_format($c->valor_consignado, 0, ',', '.') }}</td>
                    <td>{{ $c->fecha_consignacion?->format('d/m/Y') }}</td>
                    <td class="amount interest">{{ $c->interes_aplicado ? '$ ' . number_format($c->interes_aplicado, 0, ',', '.') : '—' }}</td>
                    <td class="amount">$ {{ number_format($c->total_con_interes ?: $c->valor_consignado, 0, ',', '.') }}</td>
                    <td>{{ $c->comprobante_tipo ?? 'Efectivo' }}</td>
                    <td>{{ $c->creador?->name ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} &middot; XIANDS v1.0
    </div>
</body>
</html>
