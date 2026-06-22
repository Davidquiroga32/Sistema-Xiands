@extends('layouts.app')

@section('title', 'Editar Consignaci&oacute;n')
@section('page_title', 'Editar Consign.')

@section('content')
    <div style="padding:0.85rem 1.25rem;">
        <a href="{{ route('consignaciones.show', $consignacion) }}" class="icon-btn" style="display:inline-flex;text-decoration:none;">
            &#8592;
        </a>
    </div>

    <form method="POST" action="{{ route('consignaciones.update', $consignacion) }}" enctype="multipart/form-data" style="padding:0.5rem 1.25rem 2rem;" x-data="{ preview: null, previewType: null, fileName: null }">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div style="background:rgba(220,38,38,0.1);border:1px solid rgba(220,38,38,0.2);border-radius:12px;padding:0.75rem 1rem;margin-bottom:1.5rem;">
                <p style="color:#f87171;font-size:0.8rem;margin:0;">Corrige los errores antes de continuar.</p>
            </div>
        @endif

        <div class="field-dark">
            <label class="label-dark" for="persona_id">Persona</label>
            <select id="persona_id" name="persona_id" class="input-dark" required style="color-scheme:dark;">
                @foreach ($personas as $p)
                    <option value="{{ $p->id }}" {{ old('persona_id', $consignacion->persona_id) == $p->id ? 'selected' : '' }}>
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
            value="{{ $consignacion->valor_consignado }}"
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
                value="{{ old('fecha_consignacion', $consignacion->fecha_consignacion->format('Y-m-d')) }}"
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
            >{{ old('observacion', $consignacion->observacion) }}</textarea>
            @error('observacion')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="comprobante">Comprobante</label>
            @if ($consignacion->comprobante_path)
                <div style="font-size:0.7rem;color:var(--silver-dark);margin-bottom:0.5rem;">
                    Archivo actual: {{ basename($consignacion->comprobante_path) }}
                </div>
            @endif
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
                        @change="const f = $event.target.files[0]; fileName = f ? f.name : null; if (f && f.type.startsWith('image/')) { previewType = 'image'; preview = URL.createObjectURL(f); } else if (f) { previewType = 'pdf'; preview = URL.createObjectURL(f); } else { previewType = null; preview = null; }"
                        x-ref="fileInput"
                    >
                </div>
                <button type="button"
                    x-show="previewType"
                    x-cloak
                    @click="preview = null; previewType = null; fileName = null; $refs.fileInput.value = ''"
                    class="icon-btn"
                    style="width:42px;height:42px;flex-shrink:0;"
                    title="Eliminar archivo">
                    &#128465;
                </button>
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
                Actualizar Consignaci&oacute;n
            </button>
        </div>
    </form>
@endsection
