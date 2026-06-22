<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            window.addEventListener('toast', (e) => {
                this.message = e.detail.message;
                this.type = e.detail.type || 'success';
                this.show = true;
                setTimeout(() => this.show = false, 3500);
            });
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-16 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-16 opacity-0"
    class="fixed bottom-24 left-4 right-4 z-[300] flex justify-center pointer-events-none"
    style="display:none;"
    x-cloak
>
    <div
        :class="type === 'success' ? 'bg-[rgba(74,122,74,0.95)] border-[rgba(90,154,90,0.4)]' : 'bg-[rgba(180,60,60,0.95)] border-[rgba(200,80,80,0.4)]'"
        class="px-5 py-3 rounded-xl border backdrop-blur-xl shadow-2xl pointer-events-auto flex items-center gap-3 max-w-sm"
    >
        <span x-text="type === 'success' ? '&#10003;' : '&#10005;'" class="text-sm"></span>
        <span x-text="message" class="text-sm font-medium text-white" style="font-size:0.8rem;"></span>
    </div>
</div>

@if (session('success'))
    <script>
        document.addEventListener('alpine:init', () => {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message: @json(session('success')), type: 'success' }
            }));
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('alpine:init', () => {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message: 'Corrige los errores antes de continuar.', type: 'error' }
            }));
        });
    </script>
@endif
