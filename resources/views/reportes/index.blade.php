@extends('layouts.app')

@section('title', 'Reportes')
@section('page_title', 'Reportes')

@section('content')
<div x-data="{ activeTab: 'exportar', exportTipo: 'consignaciones', fechaDesde: '', fechaHasta: '' }">
    <div style="padding:1.2rem 1.25rem;">
        <p style="font-size:0.8rem;color:var(--silver-dark);letter-spacing:0.04em;">
            Exporta reportes y revisa el historial de cambios.
        </p>
    </div>

    <div style="padding:0 1.25rem 1.2rem;">
        <div class="card-dark" style="padding:0.4rem;display:flex;gap:0.3rem;">
            <button @click="activeTab = 'exportar'"
                class="flex-1 text-center py-3 rounded-xl font-medium text-sm transition-all duration-200 cursor-pointer"
                style="border:none;font-family:'Inter',sans-serif;font-size:0.78rem;letter-spacing:0.05em;"
                :style="activeTab === 'exportar'
                    ? 'background:linear-gradient(135deg,#2a2a2a,#3a3a3a);color:var(--silver-bright);box-shadow:0 2px 8px rgba(0,0,0,0.3);'
                    : 'background:transparent;color:var(--silver-dark);'">
                &#9632;&nbsp;Exportar
            </button>
            <button @click="activeTab = 'auditoria'"
                class="flex-1 text-center py-3 rounded-xl font-medium text-sm transition-all duration-200 cursor-pointer"
                style="border:none;font-family:'Inter',sans-serif;font-size:0.78rem;letter-spacing:0.05em;"
                :style="activeTab === 'auditoria'
                    ? 'background:linear-gradient(135deg,#2a2a2a,#3a3a3a);color:var(--silver-bright);box-shadow:0 2px 8px rgba(0,0,0,0.3);'
                    : 'background:transparent;color:var(--silver-dark);'">
                &#9650;&nbsp;Auditor&iacute;a
            </button>
        </div>
    </div>

    {{-- TAB: EXPORTAR --}}
    <div x-show="activeTab === 'exportar'" x-transition.opacity>
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
                <div style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:var(--silver-bright);">{{ $consignacionesHoy }}</div>
                <div style="font-size:0.58rem;color:var(--silver-dark);letter-spacing:0.05em;text-transform:uppercase;margin-top:0.1rem;">Consign. hoy</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;text-align:center;">
                <div style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:var(--silver-bright);">{{ $consignacionesMes }}</div>
                <div style="font-size:0.58rem;color:var(--silver-dark);letter-spacing:0.05em;text-transform:uppercase;margin-top:0.1rem;">Consign. del mes</div>
            </div>
        </div>

        <div class="card-dark" style="margin:0 1.25rem 1rem;padding:1.2rem;">
            <div style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:var(--silver);margin-bottom:1rem;">
                Exportar Datos
            </div>

            <div class="field-dark">
                <label class="label-dark">Tipo de reporte</label>
                <div style="display:flex;gap:0.5rem;">
                    <button type="button" @click="exportTipo = 'consignaciones'"
                        class="filter-chip" :class="exportTipo === 'consignaciones' ? 'active' : ''">
                        Consignaciones
                    </button>
                    <button type="button" @click="exportTipo = 'personas'"
                        class="filter-chip" :class="exportTipo === 'personas' ? 'active' : ''">
                        Personas
                    </button>
                </div>
            </div>

            <div x-show="exportTipo === 'consignaciones'" style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;">
                <div class="field-dark">
                    <label class="label-dark">Fecha desde</label>
                    <input type="date" x-model="fechaDesde" class="input-dark" style="color-scheme:dark;">
                </div>
                <div class="field-dark">
                    <label class="label-dark">Fecha hasta</label>
                    <input type="date" x-model="fechaHasta" class="input-dark" style="color-scheme:dark;">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-top:1rem;">
                <form method="GET" action="{{ route('reportes.pdf') }}">
                    <input type="hidden" name="tipo" x-bind:value="exportTipo">
                    <input type="hidden" name="fecha_desde" x-bind:value="fechaDesde">
                    <input type="hidden" name="fecha_hasta" x-bind:value="fechaHasta">
                    <button type="submit" class="btn-primary-dark" style="padding:0.85rem;font-size:0.78rem;">
                        &#9671; Descargar PDF
                    </button>
                </form>

                <form method="GET" action="{{ route('reportes.excel') }}">
                    <input type="hidden" name="tipo" x-bind:value="exportTipo">
                    <input type="hidden" name="fecha_desde" x-bind:value="fechaDesde">
                    <input type="hidden" name="fecha_hasta" x-bind:value="fechaHasta">
                    <button type="submit" class="btn-primary-dark" style="padding:0.85rem;font-size:0.78rem;">
                        &#9651; Descargar Excel
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- TAB: AUDITORIA --}}
    <div x-show="activeTab === 'auditoria'" x-transition.opacity>
        <div class="card-dark" style="margin:0 1.25rem;padding:1.1rem;margin-bottom:1rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.8rem;">
                <h2 style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:var(--silver);margin:0;">
                    Historial de cambios
                </h2>
                <span style="font-size:0.68rem;color:var(--silver-dark);background:rgba(255,255,255,0.04);border:1px solid var(--border);padding:0.2rem 0.6rem;border-radius:100px;">
                    {{ $auditoria->count() }} registros
                </span>
            </div>

            <div style="display:flex;flex-direction:column;gap:0.45rem;">
                @forelse ($auditoria as $audit)
                    <div style="padding:0.75rem 0.9rem;background:rgba(255,255,255,0.02);border:1px solid var(--border);border-radius:12px;transition:border-color 0.2s;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.35rem;">
                            @php
                                $eventColors = [
                                    'created' => '#7ab87a',
                                    'updated' => '#b8b84a',
                                    'deleted' => '#c06060',
                                    'restored' => '#6080c0',
                                ];
                                $eventLabels = [
                                    'created' => 'Creado',
                                    'updated' => 'Editado',
                                    'deleted' => 'Eliminado',
                                    'restored' => 'Restaurado',
                                ];
                                $color = $eventColors[$audit->event] ?? 'var(--silver-dark)';
                                $label = $eventLabels[$audit->event] ?? $audit->event;
                            @endphp
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <span style="width:6px;height:6px;border-radius:50%;background:{{ $color }};box-shadow:0 0 5px {{ $color }};flex-shrink:0;"></span>
                                <span style="font-family:'Outfit',sans-serif;font-size:0.72rem;font-weight:600;color:var(--silver-light);letter-spacing:0.04em;">
                                    {{ $audit->auditable_type ? class_basename($audit->auditable_type) : '—' }}
                                </span>
                            </div>
                            <span style="font-size:0.58rem;color:{{ $color }};letter-spacing:0.06em;padding:0.15rem 0.5rem;background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:6px;">
                                {{ $label }}
                            </span>
                        </div>
                        <div style="font-size:0.66rem;color:var(--silver-dark);display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                            <span>{{ $audit->created_at->format('d M H:i') }}</span>
                            <span style="color:var(--border-lit);">&middot;</span>
                            <span style="color:var(--silver);">{{ $audit->user?->name ?? 'Sistema' }}</span>
                            @if ($audit->url)
                                <span style="color:var(--border-lit);">&middot;</span>
                                <span style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $audit->url }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:3rem 2rem;">
                        <div style="font-size:2rem;color:var(--border-lit);margin-bottom:0.8rem;">&#9632;</div>
                        <p style="font-size:0.8rem;color:var(--silver-dark);line-height:1.5;">
                            No hay registros de auditor&iacute;a<br>disponibles a&uacute;n.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div style="padding-bottom:calc(var(--bottom-nav-h) + 2rem);"></div>
</div>
@endsection
