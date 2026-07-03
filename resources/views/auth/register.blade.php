@extends('layouts.auth')

@section('content')
    <div class="card-title" style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:600;color:var(--silver-bright);letter-spacing:0.05em;margin-bottom:0.3rem;">
        Crear cuenta
    </div>
    <div style="font-size:0.75rem;color:var(--silver-dark);letter-spacing:0.05em;margin-bottom:2rem;">
        Registrate para empezar a usar XIANDS
    </div>

    @if ($errors->any())
        <div style="background:rgba(220,38,38,0.1);border:1px solid rgba(220,38,38,0.2);border-radius:12px;padding:0.75rem 1rem;margin-bottom:1.5rem;">
            <p style="color:#f87171;font-size:0.8rem;margin:0;">
                {{ $errors->first() ?: 'Corrige los errores antes de continuar.' }}
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field-dark">
            <label class="label-dark" for="name">Nombre</label>
            <input type="text" id="name" name="name" class="input-dark"
                value="{{ old('name') }}" placeholder="Tu nombre completo"
                autocomplete="name" required autofocus>
            @error('name')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="email">Correo</label>
            <input type="email" id="email" name="email" class="input-dark"
                value="{{ old('email') }}" placeholder="nombre@correo.com"
                autocomplete="username" required>
            @error('email')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="password">Contrase&ntilde;a</label>
            <input type="password" id="password" name="password" class="input-dark"
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                autocomplete="new-password" required>
            @error('password')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="password_confirmation">Confirmar contrase&ntilde;a</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="input-dark"
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                autocomplete="new-password" required>
            @error('password_confirmation')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary-dark" style="padding:1rem;font-size:0.9rem;">
            Registrarse
        </button>
    </form>

    <div style="text-align:center;margin-top:1.5rem;">
        <a href="{{ route('login') }}" style="font-size:0.75rem;color:var(--silver-dark);text-decoration:none;letter-spacing:0.05em;transition:color 0.2s;"
            onmouseover="this.style.color='var(--silver-light)'" onmouseout="this.style.color='var(--silver-dark)'">
            &iquest;Ya tienes cuenta? Inicia sesi&oacute;n
        </a>
    </div>
@endsection
