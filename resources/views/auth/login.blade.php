@extends('layouts.auth')

@section('content')
    <div class="card-title" style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:600;color:var(--silver-bright);letter-spacing:0.05em;margin-bottom:0.3rem;">
        Bienvenido
    </div>
    <div style="font-size:0.75rem;color:var(--silver-dark);letter-spacing:0.05em;margin-bottom:2rem;">
        Ingresa tus credenciales para continuar
    </div>

    @if ($errors->any())
        <div style="background:rgba(220,38,38,0.1);border:1px solid rgba(220,38,38,0.2);border-radius:12px;padding:0.75rem 1rem;margin-bottom:1.5rem;">
            <p style="color:#f87171;font-size:0.8rem;margin:0;">
                {{ $errors->first() ?: 'Credenciales inv&aacute;lidas. Verifica tus datos.' }}
            </p>
        </div>
    @endif

    @if (session('status'))
        <div style="background:rgba(74,122,74,0.1);border:1px solid rgba(90,138,90,0.2);border-radius:12px;padding:0.75rem 1rem;margin-bottom:1.5rem;">
            <p style="color:#7ab87a;font-size:0.8rem;margin:0;">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field-dark">
            <label class="label-dark" for="email">Usuario</label>
            <div style="position:relative;">
                <span style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--silver-dark);font-size:0.9rem;pointer-events:none;">&#9671;</span>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="input-dark"
                    style="padding-left:2.7rem;"
                    value="{{ old('email') }}"
                    placeholder="nombre@xiands.com"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>
            @error('email')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-dark">
            <label class="label-dark" for="password">Contrase&ntilde;a</label>
            <div style="position:relative;" x-data="{ show: false }">
                <span style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--silver-dark);font-size:0.9rem;pointer-events:none;">&#9632;</span>
                <input
                    :type="show ? 'text' : 'password'"
                    id="password"
                    name="password"
                    class="input-dark"
                    style="padding-left:2.7rem;padding-right:2.7rem;"
                    placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                    autocomplete="current-password"
                    required
                >
                <button
                    type="button"
                    @click="show = !show"
                    style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--silver-dark);cursor:pointer;font-size:0.85rem;padding:0;transition:color 0.2s;"
                    :style="show ? 'color: var(--silver-light)' : 'color: var(--silver-dark)'"
                    x-text="show ? '&#128064;' : '&#128065;'"
                ></button>
            </div>
            @error('password')
                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
            @enderror

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="display:block;text-align:right;font-size:0.7rem;color:var(--silver-dark);text-decoration:none;margin-top:0.5rem;letter-spacing:0.05em;transition:color 0.2s;"
                   onmouseover="this.style.color='var(--silver-light)'" onmouseout="this.style.color='var(--silver-dark)'">
                    &iquest;Olvidaste tu contrase&ntilde;a?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary-dark" style="padding:1rem;font-size:0.9rem;">
            Ingresar
        </button>
    </form>

    <div style="display:flex;align-items:center;gap:1rem;margin:1.5rem 0;">
        <div style="flex:1;height:1px;background:var(--border);"></div>
        <span style="font-size:0.65rem;color:var(--silver-dark);letter-spacing:0.1em;text-transform:uppercase;">o contin&uacute;a con</span>
        <div style="flex:1;height:1px;background:var(--border);"></div>
    </div>

    <button
        type="button"
        disabled
        style="width:100%;padding:0.85rem;border:1px solid var(--border);border-radius:12px;background:transparent;color:var(--silver);font-family:'Inter',sans-serif;font-size:0.8rem;display:flex;align-items:center;justify-content:center;gap:0.6rem;opacity:0.6;cursor:not-allowed;"
    >
        <span>&#128376;</span>
        <span>Acceso biom&eacute;trico</span>
    </button>
@endsection
