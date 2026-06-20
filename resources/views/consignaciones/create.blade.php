@extends('layouts.app')

@section('title', 'Nueva Consignaci&oacute;n')
@section('page_title', 'Nueva Consign.')

@section('content')
    <div style="padding:0.85rem 1.25rem;">
        <a href="{{ route('consignaciones.index') }}" class="icon-btn" style="display:inline-flex;text-decoration:none;">
            &#8592;
        </a>
    </div>

    <form method="POST" action="{{ route('consignaciones.store') }}" enctype="multipart/form-data" style="padding:0.5rem 1.25rem 2rem;" x-data="{ preview: null }">
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

        <div class="field-dark">
            <label class="label-dark" for="valor_consignado">Valor consignado</label>
            <input
                type="number"
                id="valor_consignado"
                name="valor_consignado"
                class="input-dark"
                value="{{ old('valor_consignado') }}"
                placeholder="$ 0.00"
                step="0.01"
                required
            >
            @error('valor_consignado')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

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
            <div style="position:relative;">
                <input
                    type="file"
                    id="comprobante"
                    name="comprobante"
                    accept="image/*,application/pdf"
                    capture="environment"
                    class="input-dark"
                    style="padding:0.7rem 1rem;"
                    @change="preview = URL.createObjectURL($event.target.files[0])"
                >
            </div>
            <template x-if="preview">
                <div style="margin-top:0.8rem;border-radius:12px;overflow:hidden;border:1px solid var(--border);">
                    <img :src="preview" alt="Vista previa" style="width:100%;max-height:200px;object-fit:cover;display:block;">
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
    </form>
@endsection
