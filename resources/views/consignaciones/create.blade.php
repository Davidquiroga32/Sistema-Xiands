@extends('layouts.app')

@section('title', 'Nueva Consignaci&oacute;n')
@section('page_title', 'Nueva Consign.')

@section('content')
    <div style="padding:0.85rem 1.25rem;">
        <a href="{{ route('consignaciones.index') }}" class="icon-btn" style="display:inline-flex;text-decoration:none;">
            &#8592;
        </a>
    </div>

    <form method="POST" action="{{ route('consignaciones.store') }}" enctype="multipart/form-data" style="padding:0.5rem 1.25rem 2rem;"
        x-data="{
            preview: null,
            previewType: null,
            fileName: null,
            ocrLoading: false,
            ocrResult: null,
            ocrOpen: false,
            async handleFileChange(e) {
                const file = e.target.files[0];
                this.fileName = file ? file.name : null;
                if (file && file.type.startsWith('image/')) {
                    this.previewType = 'image';
                    this.preview = URL.createObjectURL(file);
                } else if (file && file.type === 'application/pdf') {
                    this.previewType = 'pdf';
                    this.preview = URL.createObjectURL(file);
                } else if (file) {
                    this.previewType = 'pdf';
                    this.preview = URL.createObjectURL(file);
                } else {
                    this.previewType = null;
                    this.preview = null;
                }

                if (!file || !file.type.startsWith('image/')) return;

                this.ocrLoading = true;
                this.ocrResult = null;

                const formData = new FormData();
                formData.append('comprobante', file);
                formData.append('_token', document.querySelector('meta[name=csrf-token]').content);

                try {
                    const res = await fetch('{{ route('ocr.procesar') }}', { method: 'POST', body: formData });
                    const data = await res.json();
                    if (data.success && data.datos) {
                        this.ocrResult = data.datos;
                        this.ocrOpen = true;
                    }
                } catch (err) {
                    // OCR not available, continue silently
                } finally {
                    this.ocrLoading = false;
                }
            },
            aplicarOcr() {
                if (this.ocrResult) {
                    if (this.ocrResult.valor) {
                        window.dispatchEvent(new CustomEvent('set-money-valor_consignado', { detail: this.ocrResult.valor }));
                    }
                    if (this.ocrResult.fecha) {
                        const fecha = this.ocrResult.fecha.replace(/[\/\-\.]/g, '-');
                        const parts = fecha.split('-');
                        if (parts[2]?.length === 4) {
                            document.getElementById('fecha_consignacion').value = parts[2] + '-' + parts[1] + '-' + parts[0];
                        }
                    }
                    if (this.ocrResult.referencia || this.ocrResult.banco) {
                        const ref = [this.ocrResult.referencia, this.ocrResult.banco].filter(Boolean).join(' — ');
                        document.getElementById('observacion').value = ref;
                    }
                }
                this.ocrOpen = false;
            }
        }">
        @csrf

        @if ($errors->any())
            <div style="background:rgba(220,38,38,0.1);border:1px solid rgba(220,38,38,0.2);border-radius:12px;padding:0.75rem 1rem;margin-bottom:1.5rem;">
                <p style="color:#f87171;font-size:0.8rem;margin:0;">Corrige los errores antes de continuar.</p>
            </div>
        @endif

        <div class="field-dark">
            <label class="label-dark" for="persona_id">Persona</label>
            <select id="persona_id" name="persona_id" class="input-dark" required style="color-scheme:dark;">
                <option value="">Seleccionar persona...</option>
                @foreach ($personas as $p)
                    <option value="{{ $p->id }}" {{ old('persona_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nombre_completo }} — {{ $p->cedula }}
                    </option>
                @endforeach
            </select>
            @error('persona_id')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <x-money-input
            name="valor_consignado"
            label="Valor consignado"
            value="{{ old('valor_consignado') }}"
            placeholder="$ 0"
            required
        />

        <div class="field-dark">
            <label class="label-dark" for="fecha_consignacion">Fecha de consignaci&oacute;n</label>
            <input
                type="date"
                id="fecha_consignacion"
                name="fecha_consignacion"
                class="input-dark"
                value="{{ old('fecha_consignacion', now()->format('Y-m-d')) }}"
                required
            >
            @error('fecha_consignacion')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="observacion">Observaci&oacute;n</label>
            <textarea
                id="observacion"
                name="observacion"
                class="input-dark"
                placeholder="Observaciones opcionales..."
                style="resize:none;height:80px;"
            >{{ old('observacion') }}</textarea>
            @error('observacion')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="comprobante">Comprobante</label>
            <div style="display:flex;gap:0.5rem;align-items:center;">
                <div style="position:relative;flex:1;">
                    <input
                        type="file"
                        id="comprobante"
                        name="comprobante"
                        accept="image/*,application/pdf"
                        capture="environment"
                        class="input-dark"
                        style="padding:0.7rem 1rem;"
                        @change="handleFileChange($event)"
                        x-ref="fileInput"
                    >
                </div>
                <button type="button"
                    x-show="previewType"
                    x-cloak
                    @click="preview = null; previewType = null; fileName = null; ocrResult = null; $refs.fileInput.value = ''"
                    class="icon-btn"
                    style="width:42px;height:42px;flex-shrink:0;"
                    title="Eliminar archivo">
                    &#128465;
                </button>
            </div>
            <div x-show="ocrLoading" style="display:flex;align-items:center;gap:0.5rem;margin-top:0.8rem;padding:0.7rem 1rem;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:10px;">
                <span class="animate-pulse" style="font-size:1.2rem;">&#9670;</span>
                <span style="font-size:0.75rem;color:var(--silver-dark);">Analizando comprobante...</span>
            </div>
            <template x-if="previewType === 'image' && preview">
                <div style="margin-top:0.8rem;border-radius:12px;overflow:hidden;border:1px solid var(--border);">
                    <img :src="preview" alt="Vista previa" style="width:100%;max-height:300px;object-fit:contain;display:block;background:#0a0a0a;">
                </div>
            </template>
            <template x-if="previewType === 'pdf' && preview">
                <div style="margin-top:0.8rem;border-radius:12px;overflow:hidden;border:1px solid var(--border);background:#333;">
                    <iframe :src="preview" style="width:100%;height:280px;border:none;display:block;" title="Vista previa PDF"></iframe>
                </div>
            </template>
            @error('comprobante')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-top:2rem;">
            <button type="submit" class="btn-primary-dark" style="padding:1rem;font-size:0.9rem;">
                Guardar Consignaci&oacute;n
            </button>
        </div>

        {{-- OCR Bottom Sheet --}}
        <div x-show="ocrOpen" x-cloak @click.self="ocrOpen = false" @keydown.escape.window="ocrOpen = false"
             style="position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.75);backdrop-filter:blur(8px);display:flex;align-items:flex-end;justify-content:center;">
            <div style="width:100%;max-width:480px;background:var(--card2);border:1px solid var(--border);border-radius:24px 24px 0 0;padding:1.5rem 1.5rem 2.5rem;animation:slideUp 0.3s ease;">
                <div style="width:36px;height:4px;border-radius:2px;background:var(--border-lit);margin:0 auto 1.5rem;"></div>
                <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:600;letter-spacing:0.05em;color:var(--silver-bright);margin-bottom:1.2rem;">
                    Datos encontrados en el comprobante
                </div>
                <div style="display:flex;flex-direction:column;gap:0.6rem;margin-bottom:1.5rem;">
                    <template x-if="ocrResult?.fecha">
                        <div class="card-dark" style="padding:0.7rem 1rem;display:flex;align-items:center;gap:0.6rem;">
                            <span style="font-size:0.9rem;">&#128197;</span>
                            <div>
                                <div style="font-size:0.6rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;">Fecha</div>
                                <div style="font-size:0.85rem;color:var(--silver-bright);" x-text="ocrResult.fecha"></div>
                            </div>
                        </div>
                    </template>
                    <template x-if="ocrResult?.valor">
                        <div class="card-dark" style="padding:0.7rem 1rem;display:flex;align-items:center;gap:0.6rem;">
                            <span style="font-size:0.9rem;">&#36;</span>
                            <div>
                                <div style="font-size:0.6rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;">Valor</div>
                                <div style="font-size:0.85rem;color:var(--silver-bright);" x-text="'$ ' + Number(ocrResult.valor).toLocaleString('es-CO')"></div>
                            </div>
                        </div>
                    </template>
                    <template x-if="ocrResult?.banco">
                        <div class="card-dark" style="padding:0.7rem 1rem;display:flex;align-items:center;gap:0.6rem;">
                            <span style="font-size:0.9rem;">&#127974;</span>
                            <div>
                                <div style="font-size:0.6rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;">Banco</div>
                                <div style="font-size:0.85rem;color:var(--silver-bright);" x-text="ocrResult.banco"></div>
                            </div>
                        </div>
                    </template>
                    <template x-if="ocrResult?.referencia">
                        <div class="card-dark" style="padding:0.7rem 1rem;display:flex;align-items:center;gap:0.6rem;">
                            <span style="font-size:0.9rem;">&#128273;</span>
                            <div>
                                <div style="font-size:0.6rem;color:var(--silver-dark);text-transform:uppercase;letter-spacing:0.1em;">Referencia</div>
                                <div style="font-size:0.85rem;color:var(--silver-bright);" x-text="ocrResult.referencia"></div>
                            </div>
                        </div>
                    </template>
                    <template x-if="!ocrResult?.fecha && !ocrResult?.valor && !ocrResult?.banco && !ocrResult?.referencia">
                        <p style="font-size:0.8rem;color:var(--silver-dark);text-align:center;padding:1rem;">
                            No se detectaron datos relevantes.
                        </p>
                    </template>
                </div>
                <div style="display:flex;gap:0.75rem;">
                    <button type="button" @click="ocrOpen = false" class="btn-outline-dark" style="min-height:48px;">
                        Ingresar manualmente
                    </button>
                    <button type="button" @click="aplicarOcr()" class="btn-solid-dark" style="min-height:48px;">
                        Usar estos datos
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
