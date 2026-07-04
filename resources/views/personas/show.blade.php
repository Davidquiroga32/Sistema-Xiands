@extends('layouts.app')

@section('title', 'Perfil')
@section('page_title', 'Perfil')

@php
    $words = explode(' ', $persona->nombre_completo);
    $initials = strtoupper(($words[0][0] ?? '') . ($words[1][0] ?? $words[0][1] ?? ''));
@endphp

@section('content')
<div x-data="{ activeTab: 'consig', newConsigOpen: false }">

    <div style="padding:0.85rem 1.25rem 0;display:flex;gap:1rem;align-items:center;">
        <a href="{{ url()->previous() == url()->current() ? route('personas.index') : url()->previous() }}" class="back-btn">&#8592;</a>
        <div class="top-title" style="padding:0;">Perfil</div>
        <div style="display:flex;gap:0.5rem;margin-left:auto;">
            <a href="{{ route('personas.edit', $persona) }}" class="icon-btn" style="text-decoration:none;">&#9998;</a>
        </div>
    </div>

    <div class="hero" style="padding:1.5rem 1.25rem 1.2rem;background:linear-gradient(180deg,rgba(30,30,30,0.3) 0%,transparent 100%);border-bottom:1px solid var(--border);">
        <div style="display:flex;align-items:center;gap:1.2rem;">
            <div style="width:68px;height:68px;border-radius:20px;background:linear-gradient(135deg,#1e1e1e,#2a2a2a);border:1px solid var(--border-lit);display:flex;align-items:center;justify-content:center;font-family:'Outfit',sans-serif;font-size:1.6rem;font-weight:700;color:var(--silver-light);flex-shrink:0;position:relative;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.4);">
                {{ $initials }}
                <div style="position:absolute;top:-30%;left:-30%;width:160%;height:160%;background:radial-gradient(circle at 30% 25%,rgba(255,255,255,0.12),transparent 55%);"></div>
            </div>
            <div style="flex:1;">
                <div style="font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:700;color:var(--white);letter-spacing:0.02em;">{{ $persona->nombre_completo }}</div>
                <div style="font-size:0.72rem;color:var(--silver-dark);margin-top:0.2rem;letter-spacing:0.05em;">{{ $persona->cedula }} &nbsp;&middot;&nbsp; {{ $persona->direccion }}</div>
                <div style="display:inline-flex;align-items:center;gap:0.3rem;margin-top:0.5rem;padding:0.25rem 0.65rem;border:1px solid rgba(74,122,74,0.3);background:rgba(74,122,74,0.1);border-radius:100px;font-size:0.65rem;color:#7ab87a;letter-spacing:0.08em;">
                    <span style="width:5px;height:5px;border-radius:50%;background:#5a9a5a;box-shadow:0 0 6px rgba(90,154,90,0.6);display:inline-block;"></span>
                    {{ $persona->deleted_at ? 'Inactivo' : 'Activo' }}
                </div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.6rem;padding:1rem 1.25rem;border-bottom:1px solid var(--border);">
        <div class="card-dark" style="padding:0.9rem 0.8rem;text-align:center;">
            <div style="font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:700;" class="gradient-text">{{ $numeroConsignaciones }}</div>
            <div style="font-size:0.6rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;margin-top:0.2rem;">Consign.</div>
        </div>
        <div class="card-dark" style="padding:0.9rem 0.8rem;text-align:center;">
            <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:700;" class="gradient-text">{{ '$ ' . number_format($totalConsignado, 0, ',', '.') }}</div>
            <div style="font-size:0.6rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;margin-top:0.2rem;">Total</div>
        </div>
        <div class="card-dark" style="padding:0.9rem 0.8rem;text-align:center;">
            <div style="font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:700;" class="gradient-text">{{ '$ ' . number_format($totalIntereses, 0, ',', '.') }}</div>
            <div style="font-size:0.6rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;margin-top:0.2rem;">Inter&eacute;s</div>
        </div>
    </div>

    <div style="padding:0.5rem 1.25rem 1rem;">
        <div class="card-dark" style="padding:0.4rem;display:flex;gap:0.3rem;">
            <button @click="activeTab = 'consig'"
                class="flex-1 text-center py-3 rounded-xl font-medium text-sm transition-all duration-200 cursor-pointer"
                style="border:none;font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.04em;"
                :style="activeTab === 'consig'
                    ? 'background:linear-gradient(135deg,#2a2a2a,#3a3a3a);color:var(--silver-bright);box-shadow:0 2px 8px rgba(0,0,0,0.3);'
                    : 'background:transparent;color:var(--silver-dark);'">
                Consignaciones
            </button>
            <button @click="activeTab = 'datos'"
                class="flex-1 text-center py-3 rounded-xl font-medium text-sm transition-all duration-200 cursor-pointer"
                style="border:none;font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.04em;"
                :style="activeTab === 'datos'
                    ? 'background:linear-gradient(135deg,#2a2a2a,#3a3a3a);color:var(--silver-bright);box-shadow:0 2px 8px rgba(0,0,0,0.3);'
                    : 'background:transparent;color:var(--silver-dark);'">
                Datos
            </button>
            <button @click="activeTab = 'stats'"
                class="flex-1 text-center py-3 rounded-xl font-medium text-sm transition-all duration-200 cursor-pointer"
                style="border:none;font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.04em;"
                :style="activeTab === 'stats'
                    ? 'background:linear-gradient(135deg,#2a2a2a,#3a3a3a);color:var(--silver-bright);box-shadow:0 2px 8px rgba(0,0,0,0.3);'
                    : 'background:transparent;color:var(--silver-dark);'">
                Estad&iacute;sticas
            </button>
        </div>
    </div>

    {{-- TAB: CONSIGNACIONES --}}
    <div x-show="activeTab === 'consig'" x-transition.opacity
         x-data="{ selectedIds: [], selectAll: false, bulkOpen: false, estadoBulk: 'finalizada' }"
         style="padding:1rem 1.25rem;padding-bottom:calc(var(--bottom-nav-h) + 1.5rem);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.8rem;">
            <h3 style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.1em;color:var(--silver);text-transform:uppercase;">Historial</h3>
            <div style="display:flex;gap:0.5rem;">
                <button @click="bulkOpen = true"
                        style="display:flex;align-items:center;gap:0.3rem;padding:0.5rem 0.8rem;border-radius:10px;background:linear-gradient(135deg,#2a2a2a,#3a3a3a);border:1px solid var(--border-lit);color:var(--silver-bright);font-family:'Outfit',sans-serif;font-size:0.68rem;font-weight:600;letter-spacing:0.06em;cursor:pointer;transition:all 0.2s;">
                    <svg style="width:0.8rem;height:0.8rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2H2v10l9.3 9.3a1 1 0 0 0 1.4 0l6.6-6.6a1 1 0 0 0 0-1.4L12 2Z"/>
                        <path d="M7 7h.01"/>
                    </svg>
                    Estado
                    <span x-show="selectedIds.length > 0" x-text="selectedIds.length"
                          style="background:var(--silver);color:var(--black);font-size:0.6rem;font-weight:700;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;padding:0 4px;"></span>
                </button>
                <button @click="newConsigOpen = true"
                        style="display:flex;align-items:center;gap:0.4rem;padding:0.5rem 1rem;border-radius:10px;background:linear-gradient(135deg,#222,#333);border:1px solid var(--border-lit);color:var(--silver-bright);font-family:'Outfit',sans-serif;font-size:0.72rem;font-weight:600;letter-spacing:0.08em;cursor:pointer;transition:all 0.2s;">
                    <span>&#43;</span> Nueva
                </button>
            </div>
        </div>

        @php
            $currentEstado = null;
        @endphp

        <div style="display:flex;flex-direction:column;gap:0.55rem;">
            @forelse ($consignaciones as $i => $consig)
                @if ($currentEstado !== $consig->estado)
                    @php $currentEstado = $consig->estado; @endphp
                    <div style="padding:0.5rem 0 0.2rem;display:flex;align-items:center;gap:0.6rem;">
                        <span style="font-size:0.58rem;letter-spacing:0.1em;text-transform:uppercase;font-weight:600;
                            {{ $consig->estado === 'vigente' ? 'color:#7ab87a;' : 'color:var(--silver-dark);' }}">
                            {{ $consig->estado === 'vigente' ? 'En vigencia' : 'Finalizadas' }}
                        </span>
                        <span style="flex:1;height:1px;background:var(--border);"></span>
                    </div>
                @endif
                <div class="card-dark-hover" style="padding:0.9rem 1rem;text-decoration:none;display:flex;align-items:center;gap:0.6rem;position:relative;overflow:hidden;animation:fadeUpCard 0.4s {{ $i * 0.05 }}s ease both;
                    opacity:{{ $consig->estado === 'finalizada' ? '0.65' : '1' }};">
                    <input type="checkbox"
                           value="{{ $consig->id }}"
                           x-model="selectedIds"
                           x-bind:style="selectedIds.includes('{{ $consig->id }}')
                               ? 'appearance:none;-webkit-appearance:none;width:20px;height:20px;min-width:20px;border:2px solid var(--silver);border-radius:5px;background:var(--silver);cursor:pointer;flex-shrink:0;position:relative;outline:none;'
                               : 'appearance:none;-webkit-appearance:none;width:20px;height:20px;min-width:20px;border:2px solid #555;border-radius:5px;background:rgba(255,255,255,0.06);cursor:pointer;flex-shrink:0;position:relative;outline:none;'"
                    >
                    <a href="{{ route('consignaciones.show', $consig) }}" style="text-decoration:none;display:flex;flex:1;align-items:center;justify-content:space-between;min-width:0;">
                        <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,{{ $consig->estado === 'finalizada' ? '#444,#2a2a2a' : '#555,#333' }});border-radius:2px 0 0 2px;"></div>
                        <div style="flex:1;padding-left:0.3rem;">
                            <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:700;" class="gradient-text">
                                {{ '$ ' . number_format($consig->valor_consignado, 0, ',', '.') }}
                            </div>
                            <div style="font-size:0.68rem;color:var(--silver-dark);margin-top:0.2rem;display:flex;align-items:center;gap:0.5rem;">
                                <span style="color:var(--silver);">{{ \Carbon\Carbon::parse($consig->fecha_consignacion)->format('d M Y') }}</span>
                                <span>&middot;</span>
                                <span>{{ $consig->observacion ? Str::limit($consig->observacion, 25) : 'Sin ref.' }}</span>
                            </div>
                            @if ($consig->interes_aplicado > 0)
                                @php $tasa = $consig->tasa_aplicada ?? ($persona->tasa_interes ?? null); @endphp
                                <div style="font-size:0.7rem;color:#7a9a7a;margin-top:0.3rem;">
                                    +{{ '$ ' . number_format($consig->interes_aplicado, 0, ',', '.') }} inter&eacute;s
                                    @if ($tasa)
                                        <span>({{ $tasa }}%)</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div style="text-align:right;display:flex;flex-direction:column;align-items:flex-end;gap:0.3rem;">
                            <span style="font-size:0.5rem;letter-spacing:0.08em;text-transform:uppercase;padding:0.15rem 0.45rem;border-radius:100px;font-weight:600;
                                {{ $consig->estado === 'vigente' ? 'color:#7ab87a;background:rgba(74,154,74,0.12);border:1px solid rgba(74,154,74,0.25);' : 'color:var(--silver-dark);background:rgba(255,255,255,0.03);border:1px solid var(--border);' }}">
                                {{ $consig->estado === 'vigente' ? 'Vigente' : 'Finalizada' }}
                            </span>
                            <div style="font-size:0.62rem;letter-spacing:0.08em;text-transform:uppercase;color:var(--silver-dark);background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:6px;padding:0.2rem 0.5rem;">
                                {{ $consig->comprobante_tipo ? Str::limit($consig->comprobante_tipo, 10) : 'Efectivo' }}
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div style="text-align:center;padding:2rem 1rem;">
                    <div style="font-size:2rem;color:var(--border-lit);margin-bottom:1rem;">&#9671;</div>
                    <p style="font-size:0.8rem;color:var(--silver-dark);">Sin consignaciones registradas.</p>
                </div>
            @endforelse
        </div>

        {{-- BULK BOTTOM SHEET --}}
        <div x-show="bulkOpen" x-cloak @click.self="bulkOpen = false" @keydown.escape.window="bulkOpen = false"
             style="position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.8);display:flex;align-items:flex-end;justify-content:center;">
            <div style="width:100%;max-width:480px;background:#1a1a1a;border:1px solid #333;border-radius:24px 24px 0 0;padding:1.5rem 1.5rem 2.5rem;animation:slideUp 0.3s ease;">
                <div style="width:36px;height:4px;border-radius:2px;background:#444;margin:0 auto 1.5rem;"></div>
                <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:600;letter-spacing:0.05em;color:#e0e0e0;margin-bottom:0.5rem;">
                    Cambiar estado de consignaciones
                </div>
                <div style="font-size:0.72rem;color:#888;margin-bottom:1.2rem;">
                    <span x-text="selectedIds.length"></span> seleccionadas
                </div>
                <form method="POST" action="{{ route('personas.cambiar-estado', $persona) }}"
                      x-on:submit.prevent="if(selectedIds.length === 0) return; $el.submit(); bulkOpen = false">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" x-bind:value="id">
                    </template>
                    <input type="hidden" name="estado" x-bind:value="estadoBulk">
                    <div style="display:flex;gap:0.6rem;margin-bottom:1.2rem;">
                        <div @click="estadoBulk = 'vigente'"
                             style="flex:1;padding:0.85rem 0.5rem;border-radius:12px;border:2px solid #333;background:rgba(255,255,255,0.03);cursor:pointer;text-align:center;display:flex;flex-direction:column;gap:0.3rem;transition:all 0.2s;"
                             x-bind:style="estadoBulk === 'vigente' ? 'border-color:#6aaa6a;background:rgba(106,170,106,0.1);' : ''">
                            <span style="font-size:0.75rem;font-weight:600;color:#6aaa6a;">&#9651; Vigente</span>
                            <span style="font-size:0.55rem;color:#888;">Activa en c&aacute;lculos</span>
                        </div>
                        <div @click="estadoBulk = 'finalizada'"
                             style="flex:1;padding:0.85rem 0.5rem;border-radius:12px;border:2px solid #333;background:rgba(255,255,255,0.03);cursor:pointer;text-align:center;display:flex;flex-direction:column;gap:0.3rem;transition:all 0.2s;"
                             x-bind:style="estadoBulk === 'finalizada' ? 'border-color:#666;background:rgba(255,255,255,0.06);' : ''">
                            <span style="font-size:0.75rem;font-weight:600;color:#aaa;">&#9661; Finalizada</span>
                            <span style="font-size:0.55rem;color:#888;">Solo historial</span>
                        </div>
                    </div>
                    <div style="display:flex;gap:0.75rem;">
                        <button type="button" @click="bulkOpen = false" class="btn-outline-dark">Cancelar</button>
                        <button type="submit" class="btn-solid-dark" x-bind:disabled="selectedIds.length === 0">Aplicar</button>
                    </div>
                </form>
            </div>
        </div>

        @if ($consignaciones->hasPages())
            <div style="margin-top:1rem;">
                {{ $consignaciones->links() }}
            </div>
        @endif
    </div>

    {{-- TAB: DATOS --}}
    <div x-show="activeTab === 'datos'" x-transition.opacity style="padding:1rem 1.25rem;padding-bottom:calc(var(--bottom-nav-h) + 1.5rem);">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;">
            <div class="card-dark" style="padding:0.85rem 0.9rem;grid-column:1/-1;">
                <div class="label-dark" style="margin-bottom:0.3rem;">Nombre completo</div>
                <div style="font-size:0.88rem;color:var(--silver-bright);font-weight:500;">{{ $persona->nombre_completo }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div class="label-dark" style="margin-bottom:0.3rem;">Documento</div>
                <div style="font-size:0.88rem;color:var(--silver-bright);font-weight:500;">{{ $persona->cedula }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div class="label-dark" style="margin-bottom:0.3rem;">Tel&eacute;fono</div>
                <div style="font-size:0.88rem;color:var(--silver-bright);font-weight:500;">{{ $persona->numero_telefono ?: '—' }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div class="label-dark" style="margin-bottom:0.3rem;">Direcci&oacute;n</div>
                <div style="font-size:0.88rem;color:var(--silver-bright);font-weight:500;">{{ $persona->direccion ?: '—' }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div class="label-dark" style="margin-bottom:0.3rem;">Fecha registro</div>
                <div style="font-size:0.88rem;color:var(--silver-bright);font-weight:500;">{{ $persona->created_at->format('d M Y') }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;grid-column:1/-1;">
                <div class="label-dark" style="margin-bottom:0.3rem;">Correo</div>
                <div style="font-size:0.88rem;color:var(--silver-bright);font-weight:500;">{{ $persona->correo_electronico ?: '—' }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;grid-column:1/-1;">
                <div class="label-dark" style="margin-bottom:0.3rem;">Codeudor</div>
                <div style="font-size:0.88rem;color:var(--silver-bright);font-weight:500;">{{ $persona->nombre_codeudor ?: '—' }}</div>
            </div>
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div class="label-dark" style="margin-bottom:0.3rem;">Estado</div>
                <div style="font-size:0.88rem;color:{{ $persona->deleted_at ? 'var(--silver-dark)' : '#7ab87a' }};font-weight:500;">{{ $persona->deleted_at ? 'Inactivo' : 'Activo' }}</div>
            </div>
            @if (Auth::user()->hasRole('administradora'))
                <div class="card-dark" style="padding:0.85rem 0.9rem;grid-column:1/-1;">
                    <div class="label-dark" style="margin-bottom:0.5rem;">Administraci&oacute;n</div>
                    @if ($persona->deleted_at)
                        <form method="POST" action="{{ route('personas.restore', $persona) }}">
                            @csrf
                            <button type="submit" class="btn-primary-dark" style="padding:0.7rem 1rem;font-size:0.78rem;background:linear-gradient(135deg,#1e3a1e,#2a4a2a);">
                                Reactivar persona
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('personas.deactivate', $persona) }}">
                            @csrf
                            <button type="submit" class="btn-primary-dark" style="padding:0.7rem 1rem;font-size:0.78rem;background:linear-gradient(135deg,#3a1e1e,#4a2a2a);">
                                Desactivar persona
                            </button>
                        </form>
                    @endif
                </div>
            @endif
            <div class="card-dark" style="padding:0.85rem 0.9rem;">
                <div class="label-dark" style="margin-bottom:0.3rem;">Categor&iacute;a</div>
                <div style="font-size:0.88rem;color:var(--silver-bright);font-weight:500;">{{ $numeroConsignaciones >= 10 ? 'Alto valor' : 'Regular' }}</div>
            </div>
        </div>
    </div>

    {{-- TAB: ESTADISTICAS --}}
    <div x-show="activeTab === 'stats'" x-transition.opacity style="padding:1rem 1.25rem;padding-bottom:calc(var(--bottom-nav-h) + 1.5rem);">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;margin-bottom:1rem;">
            <div class="card-dark" style="padding:1.1rem 1rem;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-20px;right:-20px;width:60px;height:60px;border-radius:50%;background:radial-gradient(circle,rgba(150,150,150,0.06),transparent);"></div>
                <div style="font-size:1.2rem;margin-bottom:0.5rem;color:var(--silver-dark);">&#9651;</div>
                <div class="gradient-text" style="font-family:'Outfit',sans-serif;font-size:1.4rem;font-weight:700;line-height:1.1;">{{ $numeroConsignaciones }}</div>
                <div style="font-size:0.65rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;margin-top:0.3rem;">Consignaciones</div>
            </div>
            <div class="card-dark" style="padding:1.1rem 1rem;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-20px;right:-20px;width:60px;height:60px;border-radius:50%;background:radial-gradient(circle,rgba(150,150,150,0.06),transparent);"></div>
                <div style="font-size:1.2rem;margin-bottom:0.5rem;color:var(--silver-dark);">&#36;</div>
                <div class="gradient-text" style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;line-height:1.1;">{{ '$ ' . number_format($totalConsignado, 0, ',', '.') }}</div>
                <div style="font-size:0.65rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;margin-top:0.3rem;">Total consignado</div>
            </div>
            <div class="card-dark" style="padding:1.1rem 1rem;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-20px;right:-20px;width:60px;height:60px;border-radius:50%;background:radial-gradient(circle,rgba(150,150,150,0.06),transparent);"></div>
                <div style="font-size:1.2rem;margin-bottom:0.5rem;color:var(--silver-dark);">&#9670;</div>
                <div class="gradient-text" style="font-family:'Outfit',sans-serif;font-size:1.4rem;font-weight:700;line-height:1.1;">{{ '$ ' . number_format($totalIntereses, 0, ',', '.') }}</div>
                <div style="font-size:0.65rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;margin-top:0.3rem;">Intereses</div>
            </div>
            <div class="card-dark" style="padding:1.1rem 1rem;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-20px;right:-20px;width:60px;height:60px;border-radius:50%;background:radial-gradient(circle,rgba(150,150,150,0.06),transparent);"></div>
                <div style="font-size:1.2rem;margin-bottom:0.5rem;color:var(--silver-dark);">&#8734;</div>
                <div class="gradient-text" style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;line-height:1.1;">{{ '$ ' . number_format($totalConInteres, 0, ',', '.') }}</div>
                <div style="font-size:0.65rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;margin-top:0.3rem;">Total + Intereses</div>
            </div>
        </div>

        <div class="card-dark" style="padding:1.1rem;margin-bottom:0.6rem;">
            <div style="font-size:0.72rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.8rem;">&Uacute;ltimos 7 meses</div>
            <div style="display:flex;align-items:flex-end;gap:0.35rem;height:80px;">
                @php
                    $months = [];
                    $monthVals = [];
                    for ($m = 6; $m >= 0; $m--) {
                        $dt = now()->subMonths($m);
                        $months[] = $dt->translatedFormat('M');
                        $val = $persona->consignaciones()->vigente()->whereMonth('fecha_consignacion', $dt->month)->whereYear('fecha_consignacion', $dt->year)->sum('valor_consignado');
                        $monthVals[] = $val;
                    }
                    $maxVal = max($monthVals) ?: 1;
                @endphp
                @foreach ($monthVals as $j => $val)
                    <div style="flex:1;border-radius:4px 4px 0 0;height:{{ ($val/$maxVal*100) }}%;background:linear-gradient(180deg,rgba(180,180,180,0.4),rgba(100,100,100,0.2));position:relative;transition:all 0.4s ease;cursor:pointer;min-height:4px;"
                         title="{{ '$ ' . number_format($val, 0, ',', '.') }}"></div>
                @endforeach
            </div>
            <div style="display:flex;gap:0.35rem;">
                @foreach ($months as $m)
                    <div style="flex:1;text-align:center;font-size:0.55rem;color:var(--silver-dark);margin-top:0.3rem;letter-spacing:0.05em;">{{ $m }}</div>
                @endforeach
            </div>
        </div>

        @if (Auth::user()->hasRole('administradora'))
            <form method="POST" action="{{ route('personas.interes-batch', $persona) }}" x-data="{ rate: {{ $persona->tasa_interes ?? 5 }} }">
                @csrf
                <div class="card-dark" style="padding:1.2rem;margin-top:0.6rem;border-color:var(--border-lit);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                        <div style="font-family:'Outfit',sans-serif;font-size:0.85rem;font-weight:600;color:var(--silver-bright);letter-spacing:0.05em;">Tasa de Inter&eacute;s</div>
                        <div style="padding:0.3rem 0.8rem;border-radius:100px;background:rgba(180,180,180,0.08);border:1px solid var(--border-lit);font-family:'Outfit',sans-serif;font-size:0.85rem;font-weight:700;color:var(--silver-bright);" x-text="rate + '%'"></div>
                    </div>
                    <input type="hidden" name="tasa" x-bind:value="rate">
                    <div style="margin-bottom:0.8rem;">
                        <input type="range" min="0.5" max="10" step="0.5" x-model="rate"
                               style="width:100%;-webkit-appearance:none;height:4px;border-radius:2px;background:linear-gradient(90deg,var(--silver) 0%,var(--silver) calc(var(--pos) * 1%),var(--border-lit) calc(var(--pos) * 1%),var(--border-lit) 100%);outline:none;cursor:pointer;"
                               :style="'--pos:' + ((rate - 0.5) / 9.5 * 100)">
                    </div>
                    <div style="display:flex;gap:0.4rem;margin-bottom:1rem;">
                        <button type="button" @click="rate = 1" style="flex:1;padding:0.4rem;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--silver-dark);font-family:'Inter',sans-serif;font-size:0.7rem;cursor:pointer;transition:all 0.2s;" :style="rate === 1 ? 'border-color:var(--silver-dark);color:var(--silver-light);background:rgba(255,255,255,0.04);' : ''">1%</button>
                        <button type="button" @click="rate = 2.5" style="flex:1;padding:0.4rem;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--silver-dark);font-family:'Inter',sans-serif;font-size:0.7rem;cursor:pointer;transition:all 0.2s;" :style="rate === 2.5 ? 'border-color:var(--silver-dark);color:var(--silver-light);background:rgba(255,255,255,0.04);' : ''">2.5%</button>
                        <button type="button" @click="rate = 5" style="flex:1;padding:0.4rem;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--silver-dark);font-family:'Inter',sans-serif;font-size:0.7rem;cursor:pointer;transition:all 0.2s;" :style="rate === 5 ? 'border-color:var(--silver-dark);color:var(--silver-light);background:rgba(255,255,255,0.04);' : ''">5%</button>
                        <button type="button" @click="rate = 7.5" style="flex:1;padding:0.4rem;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--silver-dark);font-family:'Inter',sans-serif;font-size:0.7rem;cursor:pointer;transition:all 0.2s;" :style="rate === 7.5 ? 'border-color:var(--silver-dark);color:var(--silver-light);background:rgba(255,255,255,0.04);' : ''">7.5%</button>
                    </div>
                    <div style="font-size:0.6rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.5rem;">Aplicar a consignaciones</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-bottom:0.8rem;">
                        <div>
                            <label class="label-dark" style="font-size:0.55rem;">Desde</label>
                            <input type="date" name="fecha_desde" class="input-dark" style="color-scheme:dark;font-size:0.75rem;padding:0.5rem;">
                        </div>
                        <div>
                            <label class="label-dark" style="font-size:0.55rem;">Hasta</label>
                            <input type="date" name="fecha_hasta" class="input-dark" style="color-scheme:dark;font-size:0.75rem;padding:0.5rem;">
                        </div>
                    </div>
                    <div style="font-size:0.55rem;color:var(--silver-dark);margin-bottom:0.6rem;">Deja las fechas vac&iacute;as para aplicar a todas.</div>
                    <button type="submit" style="width:100%;margin-top:0.2rem;padding:0.75rem;border-radius:10px;background:linear-gradient(135deg,#2a2a2a,#3a3a3a);border:1px solid var(--border-lit);color:var(--white);font-family:'Outfit',sans-serif;font-size:0.78rem;font-weight:600;letter-spacing:0.1em;cursor:pointer;transition:all 0.2s;">
                        Guardar tasa de inter&eacute;s
                    </button>
                </div>
            </form>
        @endif


    </div>

    {{-- BOTTOM SHEET: NUEVA CONSIGNACION --}}
    <div x-show="newConsigOpen" x-cloak @click.self="newConsigOpen = false" @keydown.escape.window="newConsigOpen = false"
         style="position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.75);backdrop-filter:blur(8px);display:flex;align-items:flex-end;justify-content:center;">
        <div style="width:100%;max-width:480px;background:var(--card2);border:1px solid var(--border);border-radius:24px 24px 0 0;padding:1.5rem 1.5rem 2.5rem;animation:slideUp 0.3s ease;">
            <div style="width:36px;height:4px;border-radius:2px;background:var(--border-lit);margin:0 auto 1.5rem;"></div>
            <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:600;letter-spacing:0.05em;color:var(--silver-bright);margin-bottom:1.2rem;">Nueva Consignaci&oacute;n</div>

            <form method="POST" action="{{ route('consignaciones.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="persona_id" value="{{ $persona->id }}">

                <x-money-input
                    name="valor_consignado"
                    label="Monto"
                    placeholder="$ 0"
                    required
                />

                <div style="margin-bottom:1rem;">
                    <label class="label-dark" style="font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--silver-dark);margin-bottom:0.4rem;display:block;">Fecha</label>
                    <input type="date" name="fecha_consignacion" class="input-dark" value="{{ now()->format('Y-m-d') }}" required style="font-size:0.9rem;">
                </div>

                <div style="margin-bottom:1rem;">
                    <label class="label-dark" style="font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--silver-dark);margin-bottom:0.4rem;display:block;">Comprobante</label>
                    <input type="file" name="comprobante" accept="image/*,application/pdf" capture="environment" class="input-dark" style="padding:0.7rem 1rem;">
                </div>

                <div style="margin-bottom:1rem;">
                    <label class="label-dark" style="font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--silver-dark);margin-bottom:0.4rem;display:block;">Nota</label>
                    <textarea name="observacion" class="input-dark" placeholder="Observaciones opcionales..." style="resize:none;height:70px;"></textarea>
                </div>

                <div style="display:flex;gap:0.75rem;margin-top:1.2rem;">
                    <button type="button" @click="newConsigOpen = false" class="btn-outline-dark">Cancelar</button>
                    <button type="submit" class="btn-solid-dark">Registrar</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
