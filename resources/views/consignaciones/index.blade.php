@extends('layouts.app')

@section('title', 'Consignaciones')
@section('page_title', 'Consignaciones')

@section('content')
    <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem 0.6rem;">
        <h2 style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:var(--silver);">
            &Uacute;ltimas
        </h2>
        <span style="font-size:0.7rem;color:var(--silver-dark);">{{ $consignaciones->total() }} registros</span>
    </div>

    <div style="padding:0 1.25rem;display:flex;flex-direction:column;gap:0.55rem;padding-bottom:calc(var(--bottom-nav-h) + 5rem);">
        @forelse ($consignaciones as $i => $consig)
            <a href="{{ route('consignaciones.show', $consig) }}" class="card-dark-hover" style="padding:0.9rem 1rem;text-decoration:none;display:block;position:relative;overflow:hidden;animation:fadeUpCard 0.3s {{ $i * 0.04 }}s ease both;">
                <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,#555,#333);border-radius:2px 0 0 2px;"></div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding-left:0.3rem;">
                    <div style="flex:1;min-width:0;">
                        <div style="font-family:'Outfit',sans-serif;font-size:0.95rem;font-weight:600;color:var(--silver-bright);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $consig->persona?->nombre_completo ?? 'Sin persona' }}
                        </div>
                        <div style="font-size:0.68rem;color:var(--silver-dark);margin-top:0.2rem;display:flex;align-items:center;gap:0.5rem;">
                            <span style="color:var(--silver);">{{ \Carbon\Carbon::parse($consig->fecha_consignacion)->format('d M Y') }}</span>
                            <span>&middot;</span>
                            <span>{{ $consig->observacion ? Str::limit($consig->observacion, 20) : 'Sin ref.' }}</span>
                        </div>
                        @if ($consig->interes_aplicado)
                            <div style="font-size:0.7rem;color:#7a9a7a;margin-top:0.3rem;">
                                +{{ '$ ' . number_format($consig->interes_aplicado, 0, ',', '.') }} inter&eacute;s
                            </div>
                        @endif
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:700;" class="gradient-text">
                            {{ '$ ' . number_format($consig->valor_consignado, 0, ',', '.') }}
                        </div>
                        <div style="font-size:0.62rem;letter-spacing:0.08em;text-transform:uppercase;color:var(--silver-dark);margin-top:0.2rem;">
                            {{ $consig->comprobante_tipo ? Str::limit($consig->comprobante_tipo, 10) : '—' }}
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div style="text-align:center;padding:3rem 2rem;">
                <div style="font-size:2rem;color:var(--border-lit);margin-bottom:1rem;">&#9671;</div>
                <p style="font-size:0.8rem;color:var(--silver-dark);line-height:1.6;">
                    No se encontraron consignaciones<br>registradas en el sistema.
                </p>
            </div>
        @endforelse
    </div>

    @if ($consignaciones->hasPages())
        <div style="padding:0 1.25rem 1rem;">
            {{ $consignaciones->appends(request()->query())->links() }}
        </div>
    @endif

    <a href="{{ route('consignaciones.create') }}" class="fab" title="Nueva consignaci&oacute;n">&#43;</a>
@endsection
