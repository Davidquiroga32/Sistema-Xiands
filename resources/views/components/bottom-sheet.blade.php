@props(['open' => false, 'title' => '', 'maxWidth' => '480px'])

<div
    x-data="{ open: @json($open) }"
    x-show="open"
    x-cloak
    x-on:open-bottom-sheet.window="open = true"
    x-on:close-bottom-sheet.window="open = false"
    x-on:keydown.escape.window="open = false"
    @click.self="open = false"
    class="fixed inset-0 z-[200] bg-[rgba(0,0,0,0.75)] backdrop-blur-[8px] flex items-end justify-center"
    style="display:none;"
>
    <div
        class="w-full border border-[--border] rounded-t-[24px] px-6 pt-6 pb-10 bg-[--card2]"
        style="max-width:{{ $maxWidth }};animation:slideUp 0.3s ease;"
    >
        <div class="w-9 h-1 rounded-[2px] bg-[--border-lit] mx-auto mb-6"></div>

        @if ($title)
            <div class="font-[Outfit] text-base font-semibold tracking-[0.05em] text-[--silver-bright] mb-5">
                {{ $title }}
            </div>
        @endif

        {{ $slot }}
    </div>
</div>
