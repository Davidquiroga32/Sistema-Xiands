@extends('layouts.app')

@section('title', 'Consignaci&oacute;n')
@section('page_title', 'Consignaci&oacute;n')

@section('content')
    <div style="padding:0.85rem 1.25rem 0;display:flex;gap:1rem;align-items:center;">
        <a href="{{ url()->previous() == url()->current() ? route('consignaciones.index') : url()->previous() }}" class="back-btn">&#8592;</a>
        <div class="top-title" style="padding:0;">Consignaci&oacute;n</div>
        <div style="margin-left:auto;"></div>
    </div>

    <div style="padding:1.5rem 1.25rem;" x-data="{ fullscreen: false }">
        @if ($consignacion->persona)
            <a href="{{ route('personas.show', $consignacion->persona) }}" class="card-dark-hover" style="padding:1rem 1.1rem;text-decoration:none;display:flex;align-items:center;gap:1rem;margin-bottom:1.2rem;">
                @php
                    $words = explode(' ', $consignacion->persona->nombre_completo);
                    $initials = strtoupper(($words[0][0] ?? '') . ($words[1][0] ?? $words[0][1] ?? ''));
                @endphp
                <div style="width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,#222,#2e2e2e);border:1px solid var(--border-lit);display:flex;align-items:center;justify-content:center;font-family:'Outfit',sans-serif;font-size:1rem;font-weight:700;color:var(--silver-light);flex-shrink:0;position:relative;overflow:hidden;">
                    {{ $initials }}
                    <div style="position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(circle at 30% 30%,rgba(255,255,255,0.1),transparent 50%);"></div>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-family:'Outfit',sans-serif;font-size:0.95rem;font-weight:600;color:var(--silver-bright);">
                        {{ $consignacion->persona->nombre_completo }}
                    </div>
                    <div style="font-size:0.72rem;color:var(--silver-dark);margin-top:0.2rem;">
                        {{ $consignacion->persona->cedula }}
                    </div>
                </div>
                <div style="color:var(--silver-dark);font-size:0.8rem;">&#8594;</div>
            </a>
        @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;margin-bottom:1rem;">
            <div class="card-dark" style="padding:1rem 0.9rem;text-align:center;grid-column:1/-1;">
                <div style="font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:700;" class="gradient-text">
                    {{ '$ ' . number_format($consignacion->valor_consignado, 0, ',', '.') }}
                </div>
                <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.08em;text-transform:uppercase;margin-top:0.15rem;">Valor consignado</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.3rem;">Fecha</div>
                <div style="font-size:0.9rem;color:var(--silver-bright);font-weight:500;">{{ \Carbon\Carbon::parse($consignacion->fecha_consignacion)->format('d M Y') }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.3rem;">Tipo</div>
                <div style="font-size:0.9rem;color:var(--silver-bright);font-weight:500;">{{ $consignacion->comprobante_tipo ?: 'Efectivo' }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.3rem;">Inter&eacute;s aplicado</div>
                <div style="font-size:0.9rem;color:{{ $consignacion->interes_aplicado > 0 ? '#7ab87a' : 'var(--silver-dark)' }};font-weight:500;">
                    @if ($consignacion->interes_aplicado > 0)
                        @php
                            $tasa = $consignacion->tasa_aplicada ?? ($consignacion->persona->tasa_interes ?? null);
                        @endphp
                        {{ '$ ' . number_format($consignacion->interes_aplicado, 0, ',', '.') }}
                        @if ($tasa)
                            <span style="font-size:0.72rem;">({{ $tasa }}%)</span>
                        @endif
                    @else
                        No aplicado
                    @endif
                </div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.3rem;">Total con inter&eacute;s</div>
                <div style="font-size:0.9rem;color:var(--silver-bright);font-weight:500;">
                    {{ $consignacion->total_con_interes > 0 ? '$ ' . number_format($consignacion->total_con_interes, 0, ',', '.') : '$ ' . number_format($consignacion->valor_consignado, 0, ',', '.') }}
                </div>
            </div>
            @if ($consignacion->observacion)
                <div class="card-dark" style="padding:0.85rem 0.9rem;grid-column:1/-1;">
                    <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.3rem;">Observaci&oacute;n</div>
                    <div style="font-size:0.9rem;color:var(--silver-bright);font-weight:500;">{{ $consignacion->observacion }}</div>
                </div>
            @endif
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.3rem;">Registrado por</div>
                <div style="font-size:0.9rem;color:var(--silver-bright);font-weight:500;">{{ $consignacion->creador?->name ?? '—' }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.3rem;">Fecha registro</div>
                <div style="font-size:0.9rem;color:var(--silver-bright);font-weight:500;">{{ $consignacion->created_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        @if ($consignacion->comprobante_path && isset($comprobanteUrl))
            <div class="card-dark" style="padding:1rem;margin-bottom:1rem;">
                <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.5rem;">Comprobante</div>
                @if (($consignacion->comprobante_tipo ?? '') === 'imagen')
                    <img src="{{ $comprobanteUrl }}" alt="Comprobante" style="width:100%;border-radius:8px;cursor:pointer;" @click="fullscreen = true">
                    <div x-show="fullscreen" x-cloak @click.self="fullscreen = false"
                        style="position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.95);display:flex;align-items:center;justify-content:center;">
                        <button @click="fullscreen = false"
                            style="position:absolute;top:1rem;right:1rem;z-index:10;width:44px;height:44px;border-radius:50%;border:1px solid var(--border-lit);background:var(--card);color:var(--silver-bright);font-size:1.2rem;display:flex;align-items:center;justify-content:center;cursor:pointer;">&times;</button>
                        <img src="{{ $comprobanteUrl }}" alt="Comprobante" style="max-width:100%;max-height:100vh;object-fit:contain;padding:1rem;">
                    </div>
                @else
                    <a href="{{ $comprobanteUrl }}" target="_blank" style="display:flex;align-items:center;gap:0.5rem;padding:0.75rem 1rem;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:10px;text-decoration:none;color:var(--silver-light);font-size:0.8rem;transition:all 0.2s;">
                        <span style="font-size:1.5rem;">&#9671;</span>
                        <span>Ver documento</span>
                    </a>
                @endif
            </div>
        @endif

        @if (!$consignacion->interes_aplicado && (Auth::user()->hasRole('administradora') || Auth::user()->hasRole('admin')))
            <form method="POST" action="{{ route('consignaciones.interes', $consignacion) }}" style="margin-bottom:1rem;">
                @csrf
                <button type="submit" class="btn-primary-dark" style="padding:0.85rem;font-size:0.82rem;">
                    Aplicar Inter&eacute;s (5%)
                </button>
            </form>
        @endif

        @if ($consignacion->interes_aplicado_by)
            <div style="font-size:0.65rem;color:var(--silver-dark);text-align:center;margin-bottom:1rem;">
                Inter&eacute;s aplicado por {{ $consignacion->interesAplicadoPor?->name ?? '—' }}
                el {{ $consignacion->interes_aplicado_at ? \Carbon\Carbon::parse($consignacion->interes_aplicado_at)->format('d M Y H:i') : '—' }}
            </div>
        @endif
    </div>
@endsection
