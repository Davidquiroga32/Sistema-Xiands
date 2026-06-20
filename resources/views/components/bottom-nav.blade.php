@php
    $routeName = request()->route()->getName();
@endphp

<nav class="fixed bottom-0 left-0 right-0 z-[100] bg-[rgba(10,10,10,0.95)] backdrop-blur-xl border-t border-[--border] flex items-center justify-around"
     style="min-height: var(--bottom-nav-h); padding-bottom: env(safe-area-inset-bottom);">

    <a href="{{ route('dashboard') }}"
       class="flex flex-col items-center gap-1 py-2 px-6 text-decoration-none {{ $routeName === 'dashboard' ? 'text-[--silver-light]' : 'text-[--silver-dark]' }} transition-colors duration-200 hover:text-[--silver]">
        <span style="font-size:1.1rem;">&#9632;</span>
        <span style="font-size:0.6rem;letter-spacing:0.08em;font-weight:500;">Inicio</span>
    </a>

    <a href="{{ route('personas.index') }}"
       class="flex flex-col items-center gap-1 py-2 px-6 text-decoration-none {{ str_starts_with($routeName, 'personas') ? 'text-[--silver-light]' : 'text-[--silver-dark]' }} transition-colors duration-200 hover:text-[--silver]">
        <span style="font-size:1.1rem;">&#9651;</span>
        <span style="font-size:0.6rem;letter-spacing:0.08em;font-weight:500;">Personas</span>
    </a>

    <a href="{{ route('consignaciones.index') }}"
       class="flex flex-col items-center gap-1 py-2 px-6 text-decoration-none {{ str_starts_with($routeName, 'consignaciones') ? 'text-[--silver-light]' : 'text-[--silver-dark]' }} transition-colors duration-200 hover:text-[--silver]">
        <span style="font-size:1.1rem;">&#9671;</span>
        <span style="font-size:0.6rem;letter-spacing:0.08em;font-weight:500;">Consign.</span>
    </a>

    <a href="#"
       class="flex flex-col items-center gap-1 py-2 px-6 text-decoration-none {{ str_starts_with($routeName, 'reportes') ? 'text-[--silver-light]' : 'text-[--silver-dark]' }} transition-colors duration-200 hover:text-[--silver]">
        <span style="font-size:1.1rem;">&#9670;</span>
        <span style="font-size:0.6rem;letter-spacing:0.08em;font-weight:500;">Reportes</span>
    </a>

    <a href="{{ route('profile.edit') }}"
       class="flex flex-col items-center gap-1 py-2 px-6 text-decoration-none {{ str_starts_with($routeName, 'profile') ? 'text-[--silver-light]' : 'text-[--silver-dark]' }} transition-colors duration-200 hover:text-[--silver]">
        <span style="font-size:1.1rem;">&#9675;</span>
        <span style="font-size:0.6rem;letter-spacing:0.08em;font-weight:500;">Perfil</span>
    </a>
</nav>
