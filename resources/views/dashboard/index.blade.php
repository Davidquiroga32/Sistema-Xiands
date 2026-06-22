@extends('layouts.app')

@section('title', 'Inicio')
@section('page_title', 'Inicio')

@section('content')
    <div style="padding:1.2rem 1.25rem;">
        <p style="font-size:0.8rem;color:var(--silver-dark);letter-spacing:0.04em;">
            Bienvenido, <span style="color:var(--silver-light);font-weight:600;">{{ Auth::user()->name }}</span>
        </p>
    </div>

    <div class="card-dark" style="margin:0 1.25rem 0.8rem;padding:1.1rem;">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.5rem;">
            <div style="text-align:center;">
                <div style="font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:700;" class="gradient-text">{{ $totalPersonas }}</div>
                <div style="font-size:0.55rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.08em;margin-top:0.1rem;">Personas</div>
            </div>
            <div style="text-align:center;">
                <div style="font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:700;" class="gradient-text">{{ $totalConsignaciones }}</div>
                <div style="font-size:0.55rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.08em;margin-top:0.1rem;">Consign.</div>
            </div>
            <div style="text-align:center;">
                <div style="font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:700;" class="gradient-text">{{ '$ ' . number_format($totalConsignado, 0, ',', '.') }}</div>
                <div style="font-size:0.55rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.08em;margin-top:0.1rem;">Total</div>
            </div>
            <div style="text-align:center;">
                <div style="font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:700;color:#7ab87a;">{{ '$ ' . number_format($totalIntereses, 0, ',', '.') }}</div>
                <div style="font-size:0.55rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.08em;margin-top:0.1rem;">Inter&eacute;s</div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;padding:0 1.25rem 1rem;">
        <div class="card-dark" style="padding:0.85rem 0.9rem;text-align:center;">
            <div style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:var(--silver-bright);">{{ $hoy }}</div>
            <div style="font-size:0.58rem;color:var(--silver-dark);letter-spacing:0.05em;text-transform:uppercase;margin-top:0.1rem;">Consign. hoy</div>
        </div>
        <div class="card-dark" style="padding:0.85rem 0.9rem;text-align:center;">
            <div style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:var(--silver-bright);">{{ $mesActual }}</div>
            <div style="font-size:0.58rem;color:var(--silver-dark);letter-spacing:0.05em;text-transform:uppercase;margin-top:0.1rem;">Consign. del mes</div>
        </div>
    </div>

    <div style="padding:0 1.25rem 0.5rem;display:flex;align-items:center;justify-content:space-between;">
        <h2 style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--silver);margin:0;">
            &Uacute;ltimas consignaciones
        </h2>
        <span style="font-size:0.7rem;color:var(--silver-dark);">{{ $ultimasConsignaciones->count() }} registros</span>
    </div>

    <div style="padding:0 1.25rem;display:flex;flex-direction:column;gap:0.55rem;padding-bottom:calc(var(--bottom-nav-h) + 1.5rem);">
        @forelse ($ultimasConsignaciones as $i => $consig)
            <a href="{{ route('consignaciones.show', $consig) }}" class="card-dark-hover" style="padding:0.9rem 1rem;text-decoration:none;display:block;animation:fadeUpCard 0.4s {{ $i * 0.04 }}s ease both;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div style="flex:1;min-width:0;">
                        <div style="font-family:'Outfit',sans-serif;font-size:0.95rem;font-weight:600;color:var(--silver-bright);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $consig->persona?->nombre_completo ?? 'Sin persona' }}
                        </div>
                        <div style="font-size:0.68rem;color:var(--silver-dark);margin-top:0.2rem;display:flex;align-items:center;gap:0.5rem;">
                            <span style="color:var(--silver);">{{ \Carbon\Carbon::parse($consig->fecha_consignacion)->format('d M Y') }}</span>
                            @if ($consig->interes_aplicado)
                                <span style="color:var(--border-lit);">&middot;</span>
                                <span style="color:#7a9a7a;">+{{ '$ ' . number_format($consig->interes_aplicado, 0, ',', '.') }} int.</span>
                            @endif
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:700;" class="gradient-text">
                            {{ '$ ' . number_format($consig->valor_consignado, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div style="text-align:center;padding:3rem 2rem;">
                <div style="font-size:2rem;color:var(--border-lit);margin-bottom:0.8rem;">&#9671;</div>
                <p style="font-size:0.8rem;color:var(--silver-dark);line-height:1.5;">
                    A&uacute;n no hay consignaciones<br>registradas en el sistema.
                </p>
            </div>
        @endforelse
    </div>
@endsection
