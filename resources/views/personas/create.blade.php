@extends('layouts.app')

@section('title', 'Nueva Persona')
@section('page_title', 'Nueva Persona')

@section('content')
    <div style="padding:0.85rem 1.25rem;">
        <a href="{{ route('personas.index') }}" class="icon-btn" style="display:inline-flex;text-decoration:none;">
            &#8592;
        </a>
    </div>

    <form method="POST" action="{{ route('personas.store') }}" style="padding:0.5rem 1.25rem 2rem;">
        @csrf

        <div class="field-dark">
            <label class="label-dark" for="nombre_completo">Nombre completo</label>
            <input
                type="text"
                id="nombre_completo"
                name="nombre_completo"
                class="input-dark"
                value="{{ old('nombre_completo') }}"
                placeholder="Ej: Carlos Andr&eacute;s P&eacute;rez"
                required
            >
            @error('nombre_completo')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="cedula">C&eacute;dula</label>
            <input
                type="text"
                id="cedula"
                name="cedula"
                class="input-dark"
                value="{{ old('cedula') }}"
                placeholder="CC 1020-3840028"
                required
            >
            @error('cedula')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="correo_electronico">Correo electr&oacute;nico</label>
            <input
                type="email"
                id="correo_electronico"
                name="correo_electronico"
                class="input-dark"
                value="{{ old('correo_electronico') }}"
                placeholder="correo@ejemplo.com"
            >
            @error('correo_electronico')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="numero_telefono">N&uacute;mero de tel&eacute;fono</label>
            <input
                type="tel"
                id="numero_telefono"
                name="numero_telefono"
                class="input-dark"
                value="{{ old('numero_telefono') }}"
                placeholder="+57 314 852 9631"
            >
            @error('numero_telefono')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="direccion">Direcci&oacute;n</label>
            <input
                type="text"
                id="direccion"
                name="direccion"
                class="input-dark"
                value="{{ old('direccion') }}"
                placeholder="Cra 45 #52-18, El Poblado"
            >
            @error('direccion')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="nombre_codeudor">Nombre del codeudor</label>
            <input
                type="text"
                id="nombre_codeudor"
                name="nombre_codeudor"
                class="input-dark"
                value="{{ old('nombre_codeudor') }}"
                placeholder="Nombre del codeudor"
            >
            @error('nombre_codeudor')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-top:2rem;">
            <button type="submit" class="btn-primary-dark" style="padding:1rem;font-size:0.9rem;">
                Guardar Persona
            </button>
        </div>
    </form>
@endsection
