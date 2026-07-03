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
                class="flex-1 text-center py-3 rounded-xl font-medium text-sm transition-all duration-200 cursor-pointer inline-flex items-center justify-center gap-2"
                style="border:none;font-family:'Inter',sans-serif;font-size:0.78rem;letter-spacing:0.05em;"
                :style="activeTab === 'exportar'
                    ? 'background:linear-gradient(135deg,#2a2a2a,#3a3a3a);color:var(--silver-bright);box-shadow:0 2px 8px rgba(0,0,0,0.3);'
                    : 'background:transparent;color:var(--silver-dark);'">
                <svg style="width:1rem;height:1rem;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3v11"/>
                    <path d="M8 10.5 12 14.5l4-4"/>
                    <path d="M4.5 16.5V19a1.5 1.5 0 0 0 1.5 1.5h12a1.5 1.5 0 0 0 1.5-1.5v-2.5"/>
                </svg>
                Exportar
            </button>
            <button @click="activeTab = 'auditoria'"
                class="flex-1 text-center py-3 rounded-xl font-medium text-sm transition-all duration-200 cursor-pointer inline-flex items-center justify-center gap-2"
                style="border:none;font-family:'Inter',sans-serif;font-size:0.78rem;letter-spacing:0.05em;"
                :style="activeTab === 'auditoria'
                    ? 'background:linear-gradient(135deg,#2a2a2a,#3a3a3a);color:var(--silver-bright);box-shadow:0 2px 8px rgba(0,0,0,0.3);'
                    : 'background:transparent;color:var(--silver-dark);'">
                <svg style="width:1rem;height:1rem;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3.3 2"/>
                </svg>
                Auditor&iacute;a
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
                    <button type="submit" class="btn-primary-dark inline-flex items-center justify-center gap-2" style="padding:0.85rem;font-size:0.78rem;">
                        <svg style="width:1rem;height:1rem;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 3.5h7l4 4V19a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 6 19V5A1.5 1.5 0 0 1 7 3.5Z"/>
                            <path d="M14 3.5V8h4"/>
                            <path d="M9 13h6"/>
                            <path d="M9 16h4"/>
                        </svg>
                        Descargar PDF
                    </button>
                </form>

                <form method="GET" action="{{ route('reportes.excel') }}">
                    <input type="hidden" name="tipo" x-bind:value="exportTipo">
                    <input type="hidden" name="fecha_desde" x-bind:value="fechaDesde">
                    <input type="hidden" name="fecha_hasta" x-bind:value="fechaHasta">
                    <button type="submit" class="btn-primary-dark inline-flex items-center justify-center gap-2" style="padding:0.85rem;font-size:0.78rem;">
                        <svg style="width:1rem;height:1rem;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3.5" y="4.5" width="17" height="15" rx="1.5"/>
                            <path d="M3.5 9.5h17"/>
                            <path d="M9 9.5V20"/>
                            <path d="M15 9.5V20"/>
                        </svg>
                        Descargar Excel
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
                    <div x-data="{ open: false }" style="padding:0.75rem 0.9rem;background:rgba(255,255,255,0.02);border:1px solid var(--border);border-radius:12px;transition:border-color 0.2s;">
                        <div @click="open = !open" style="cursor:pointer;display:flex;align-items:center;justify-content:space-between;margin-bottom:0.35rem;">
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
                        <div @click="open = !open" style="cursor:pointer;font-size:0.66rem;color:var(--silver-dark);display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                            <span>{{ $audit->created_at->format('d M H:i') }}</span>
                            <span style="color:var(--border-lit);">&middot;</span>
                            <span style="color:var(--silver);">{{ $audit->user?->name ?? 'Sistema' }}</span>
                            @if ($audit->url)
                                <span style="color:var(--border-lit);">&middot;</span>
                                <span style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $audit->url }}</span>
                            @endif
                        </div>
                        @php
                            $oldValues = $audit->old_values ?? [];
                            $newValues = $audit->new_values ?? [];
                        @endphp
                        <div x-show="open" style="margin-top:0.6rem;padding-top:0.6rem;border-top:1px solid var(--border);">
                            @if ($audit->event === 'created' && !empty($newValues))
                                <div style="font-size:0.62rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.4rem;">Valores creados</div>
                                @foreach ($newValues as $campo => $valor)
                                    <div style="display:flex;gap:0.5rem;font-size:0.68rem;padding:0.25rem 0;">
                                        <span style="color:var(--silver-dark);min-width:80px;flex-shrink:0;">{{ $campo }}</span>
                                        <span style="color:#7ab87a;">{{ is_scalar($valor) ? (is_bool($valor) ? ($valor ? 'Sí' : 'No') : $valor) : json_encode($valor) }}</span>
                                    </div>
                                @endforeach
                            @elseif ($audit->event === 'deleted' && !empty($oldValues))
                                <div style="font-size:0.62rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.4rem;">Valores eliminados</div>
                                @foreach ($oldValues as $campo => $valor)
                                    <div style="display:flex;gap:0.5rem;font-size:0.68rem;padding:0.25rem 0;">
                                        <span style="color:var(--silver-dark);min-width:80px;flex-shrink:0;">{{ $campo }}</span>
                                        <span style="color:#c06060;">{{ is_scalar($valor) ? (is_bool($valor) ? ($valor ? 'Sí' : 'No') : $valor) : json_encode($valor) }}</span>
                                    </div>
                                @endforeach
                            @elseif (($audit->event === 'updated' || $audit->event === 'restored') && !empty($oldValues) && !empty($newValues))
                                <div style="font-size:0.62rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.4rem;">Cambios</div>
                                @foreach ($newValues as $campo => $nuevo)
                                    @php $anterior = $oldValues[$campo] ?? null; @endphp
                                    @if ($anterior !== $nuevo)
                                        <div style="font-size:0.68rem;padding:0.3rem 0.4rem;margin-bottom:0.2rem;background:rgba(255,255,255,0.02);border-radius:6px;">
                                            <div style="color:var(--silver);margin-bottom:0.15rem;">{{ $campo }}</div>
                                            <div style="display:flex;gap:0.4rem;align-items:center;">
                                                <span style="color:#c06060;font-size:0.62rem;">{{ is_scalar($anterior) ? (is_bool($anterior) ? ($anterior ? 'Sí' : 'No') : $anterior) : json_encode($anterior) }}</span>
                                                <span style="color:var(--border-lit);">&rarr;</span>
                                                <span style="color:#7ab87a;font-size:0.62rem;">{{ is_scalar($nuevo) ? (is_bool($nuevo) ? ($nuevo ? 'Sí' : 'No') : $nuevo) : json_encode($nuevo) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:3rem 2rem;">
                        <svg style="width:2rem;height:2rem;color:var(--border-lit);margin:0 auto 0.8rem;display:block;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3.3 2"/>
                        </svg>
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
