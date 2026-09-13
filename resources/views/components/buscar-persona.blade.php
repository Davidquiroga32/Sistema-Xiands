<?php

use Livewire\Component;
use App\Models\Persona;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = 'activos';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $query = Persona::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nombre_completo', 'like', '%' . $this->search . '%')
                  ->orWhere('cedula', 'like', '%' . $this->search . '%')
                  ->orWhere('direccion', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filter === 'desactivados') {
            $query->onlyTrashed();
        } elseif ($this->filter === 'recientes') {
            $query->where('created_at', '>=', now()->subDays(8));
        }

        $query->withSum('consignaciones', 'valor_consignado')
            ->withCount('consignaciones');

        $personas = $query->latest()->paginate(12);
        $totalPersonas = Persona::count();
        $totalConsignaciones = \App\Models\Consignacion::count();
        $totalConsignado = \App\Models\Consignacion::sum('valor_consignado');

        return view('components.buscar-persona', [
            'personas' => $personas,
            'totalPersonas' => $totalPersonas,
            'totalConsignaciones' => $totalConsignaciones,
            'totalConsignado' => $totalConsignado,
        ]);
    }
};
?>

<div>
    <div class="search-section">
        <div class="search-wrap">
            <span class="search-icon">&#9740;</span>
            <input
                class="search-input"
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nombre, cédula o ciudad..."
            >
            @if($search)
                <button class="search-clear" wire:click="$set('search', '')" style="display:block;">&#10005;</button>
            @endif
        </div>
        <div class="filters-row">
            <button type="button" class="filter-chip {{ $filter === 'activos' ? 'active' : '' }}" wire:click="setFilter('activos')">Activos</button>
            <button type="button" class="filter-chip {{ $filter === 'desactivados' ? 'active' : '' }}" wire:click="setFilter('desactivados')">Desactivados</button>
            <button type="button" class="filter-chip {{ $filter === 'recientes' ? 'active' : '' }}" wire:click="setFilter('recientes')">&Uacute;ltimos 8 d&iacute;as</button>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-mini">
            <div class="val">{{ $totalPersonas }}</div>
            <div class="lbl">Personas</div>
        </div>
        <div class="stat-mini">
            <div class="val">{{ $totalConsignaciones }}</div>
            <div class="lbl">Consign.</div>
        </div>
        <div class="stat-mini">
            <div class="val" style="font-size:1rem;">${{ number_format($totalConsignado, 0, ',', '.') }}</div>
            <div class="lbl">Total</div>
        </div>
    </div>

    <div class="section-head">
        <h2>Listado</h2>
        <span>{{ $personas->total() }} registros</span>
    </div>

    <div class="people-list">
        @forelse ($personas as $persona)
            <a href="{{ route('personas.show', $persona) }}" class="person-card" wire:key="persona-{{ $persona->id }}">
                <div class="card-inner">
                    <div class="avatar">
                        @php
                            $words = explode(' ', $persona->nombre_completo);
                            $initials = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
                        @endphp
                        {{ $initials }}
                    </div>
                    <div class="person-info">
                        <div class="person-name">{{ $persona->nombre_completo }}</div>
                        <div class="person-meta">
                            <span class="status-dot {{ $persona->deleted_at ? 'inactive' : 'active' }}"></span>
                            {{ $persona->cedula }}
                            &nbsp;·&nbsp;
                            <span>{{ $persona->direccion ? \Illuminate\Support\Str::words($persona->direccion, 2, '') : '—' }}</span>
                        </div>
                    </div>
                    <div class="person-right">
                        <div class="person-amount">${{ number_format($persona->consignaciones_sum_valor_consignado ?? 0, 0, ',', '.') }}</div>
                        <div class="person-count">{{ $persona->consignaciones_count }} consign.</div>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state" style="display:block;">
                <div class="icon">&#9671;</div>
                <p>No se encontraron personas<br>con ese criterio de búsqueda.</p>
            </div>
        @endforelse
    </div>

    <div class="px-4 py-3">
        {{ $personas->links() }}
    </div>
</div>
