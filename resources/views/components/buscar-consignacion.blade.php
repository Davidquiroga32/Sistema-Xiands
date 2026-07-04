<?php

use Livewire\Component;
use App\Models\Consignacion;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $fechaDesde = '';
    public string $fechaHasta = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFechaDesde(): void
    {
        $this->resetPage();
    }

    public function updatingFechaHasta(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->fechaDesde = '';
        $this->fechaHasta = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Consignacion::with('persona');

        if (! empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('observacion', 'like', "%{$search}%")
                    ->orWhere('comprobante_tipo', 'like', "%{$search}%")
                    ->orWhereHas('persona', function ($q) use ($search) {
                        $q->where('nombre_completo', 'like', "%{$search}%")
                            ->orWhere('cedula', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($this->fechaDesde)) {
            $query->where('fecha_consignacion', '>=', $this->fechaDesde);
        }

        if (! empty($this->fechaHasta)) {
            $query->where('fecha_consignacion', '<=', $this->fechaHasta);
        }

        $consignaciones = $query->latest()->paginate(15);

        return view('components.buscar-consignacion', [
            'consignaciones' => $consignaciones,
        ]);
    }
};
?>

<div>
    {{-- FILTROS --}}
    <div style="padding:1rem 1.25rem 0.6rem;">
        <div style="position:relative;margin-bottom:0.65rem;">
            <svg style="position:absolute;left:0.9rem;top:50%;transform:translateY(-50%);width:1rem;height:1rem;color:var(--silver-dark);pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"/>
                <path d="M21 21l-4.3-4.3"/>
            </svg>
            <input
                type="search"
                wire:model.live.debounce.250ms="search"
                placeholder="Buscar persona, c&eacute;dula, observaci&oacute;n..."
                style="width:100%;padding:0.7rem 1rem 0.7rem 2.5rem;border-radius:12px;background:rgba(255,255,255,0.04);border:1px solid var(--border);color:var(--silver-bright);font-family:'Inter',sans-serif;font-size:0.82rem;outline:none;transition:border-color 0.2s;box-sizing:border-box;"
                onfocus="this.style.borderColor='var(--border-lit)'"
                onblur="this.style.borderColor='var(--border)'"
            >
            @if ($search)
                <button wire:click="$set('search', '')" style="position:absolute;right:0.6rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--silver-dark);cursor:pointer;font-size:0.85rem;padding:0.3rem 0.5rem;">&#10005;</button>
            @endif
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:0.5rem;align-items:end;">
            <div>
                <label style="display:block;font-size:0.55rem;letter-spacing:0.12em;text-transform:uppercase;color:var(--silver-dark);margin-bottom:0.3rem;">Desde</label>
                <input type="date" wire:model.live="fechaDesde"
                       style="width:100%;padding:0.55rem 0.6rem;border-radius:10px;background:rgba(255,255,255,0.04);border:1px solid var(--border);color:var(--silver-bright);font-family:'Inter',sans-serif;font-size:0.75rem;outline:none;color-scheme:dark;box-sizing:border-box;"
                       onfocus="this.style.borderColor='var(--border-lit)'" onblur="this.style.borderColor='var(--border)'">
            </div>
            <div>
                <label style="display:block;font-size:0.55rem;letter-spacing:0.12em;text-transform:uppercase;color:var(--silver-dark);margin-bottom:0.3rem;">Hasta</label>
                <input type="date" wire:model.live="fechaHasta"
                       style="width:100%;padding:0.55rem 0.6rem;border-radius:10px;background:rgba(255,255,255,0.04);border:1px solid var(--border);color:var(--silver-bright);font-family:'Inter',sans-serif;font-size:0.75rem;outline:none;color-scheme:dark;box-sizing:border-box;"
                       onfocus="this.style.borderColor='var(--border-lit)'" onblur="this.style.borderColor='var(--border)'">
            </div>
            <div>
                @if ($search || $fechaDesde || $fechaHasta)
                    <button wire:click="clearFilters"
                            style="min-height:36px;padding:0.55rem 0.7rem;border-radius:10px;border:1px solid var(--border);color:var(--silver-dark);background:transparent;font-family:'Outfit',sans-serif;font-size:0.7rem;font-weight:500;cursor:pointer;white-space:nowrap;transition:all 0.2s;">
                        Limpiar
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;padding:0.6rem 1.25rem 0.6rem;">
        <h2 style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:var(--silver);">
            &Uacute;ltimas
        </h2>
        <span style="font-size:0.7rem;color:var(--silver-dark);">{{ $consignaciones->total() }} registros</span>
    </div>

    <div style="padding:0 1.25rem;display:flex;flex-direction:column;gap:0.55rem;padding-bottom:calc(var(--bottom-nav-h) + 5rem);">
        @forelse ($consignaciones as $i => $consig)
            <a href="{{ route('consignaciones.show', $consig) }}" class="card-dark-hover" style="padding:0.9rem 1rem;text-decoration:none;display:block;position:relative;overflow:hidden;animation:fadeUpCard 0.3s {{ $i * 0.04 }}s ease both;opacity:{{ $consig->estado === 'finalizada' ? '0.65' : '1' }};" wire:key="consig-{{ $consig->id }}">
                <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,{{ $consig->estado === 'finalizada' ? '#444,#2a2a2a' : '#555,#333' }});border-radius:2px 0 0 2px;"></div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding-left:0.3rem;">
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:0.4rem;margin-bottom:0.1rem;">
                            <div style="font-family:'Outfit',sans-serif;font-size:0.95rem;font-weight:600;color:var(--silver-bright);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $consig->persona?->nombre_completo ?? 'Sin persona' }}
                            </div>
                            @if ($consig->estado !== 'vigente')
                                <span style="font-size:0.48rem;letter-spacing:0.06em;padding:0.1rem 0.35rem;border-radius:100px;color:var(--silver-dark);background:rgba(255,255,255,0.04);border:1px solid var(--border);flex-shrink:0;">Finalizada</span>
                            @endif
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
            {{ $consignaciones->links() }}
        </div>
    @endif
</div>
