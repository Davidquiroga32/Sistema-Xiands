@php
    $routeName = request()->route()->getName();
@endphp

<nav class="bottom-nav-bar fixed bottom-0 left-0 right-0 z-[100] backdrop-blur-xl border-t border-[--border] flex items-center justify-around"
     style="min-height: var(--bottom-nav-h); padding-bottom: env(safe-area-inset-bottom);">

    <a href="{{ route('dashboard') }}"
       class="flex flex-col items-center gap-1 py-2 px-6 text-decoration-none {{ $routeName === 'dashboard' ? 'text-[--silver-light]' : 'text-[--silver-dark]' }} transition-colors duration-200 hover:text-[--silver]">
        <svg style="width:1.35rem;height:1.35rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3.5 10.5 12 3.5l8.5 7"/>
            <path d="M5.5 9.5V20a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V9.5"/>
            <path d="M9.5 21v-6.5h5V21"/>
        </svg>
        <span style="font-size:0.6rem;letter-spacing:0.08em;font-weight:500;">Inicio</span>
    </a>

    <a href="{{ route('personas.index') }}"
       class="flex flex-col items-center gap-1 py-2 px-6 text-decoration-none {{ str_starts_with($routeName, 'personas') ? 'text-[--silver-light]' : 'text-[--silver-dark]' }} transition-colors duration-200 hover:text-[--silver]">
        <svg style="width:1.35rem;height:1.35rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="8" r="2.8"/>
            <path d="M3.5 20c0-3.3 2.46-5.5 5.5-5.5s5.5 2.2 5.5 5.5"/>
            <circle cx="17" cy="8.6" r="2.2"/>
            <path d="M15.6 14.9c2.53.28 4.4 2.35 4.4 5.1"/>
        </svg>
        <span style="font-size:0.6rem;letter-spacing:0.08em;font-weight:500;">Personas</span>
    </a>

    <a href="{{ route('consignaciones.index') }}"
       class="flex flex-col items-center gap-1 py-2 px-6 text-decoration-none {{ str_starts_with($routeName, 'consignaciones') ? 'text-[--silver-light]' : 'text-[--silver-dark]' }} transition-colors duration-200 hover:text-[--silver]">
        <svg style="width:1.35rem;height:1.35rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3.5" y="10.5" width="17" height="9.5" rx="1.6"/>
            <path d="M12 3v9"/>
            <path d="M8.3 8.3 12 12l3.7-3.7"/>
        </svg>
        <span style="font-size:0.6rem;letter-spacing:0.08em;font-weight:500;">Consign.</span>
    </a>

    <a href="{{ route('reportes.index') }}"
       class="flex flex-col items-center gap-1 py-2 px-6 text-decoration-none {{ str_starts_with($routeName, 'reportes') ? 'text-[--silver-light]' : 'text-[--silver-dark]' }} transition-colors duration-200 hover:text-[--silver]">
        <svg style="width:1.35rem;height:1.35rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 20V10.5"/>
            <path d="M10 20V4.5"/>
            <path d="M16 20v-7"/>
            <path d="M3 20h18"/>
        </svg>
        <span style="font-size:0.6rem;letter-spacing:0.08em;font-weight:500;">Reportes</span>
    </a>

</nav>
