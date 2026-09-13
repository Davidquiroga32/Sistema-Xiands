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
                    style="padding-left:2.7rem;padding-right:2.9rem;"
                    placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                    autocomplete="current-password"
                    required
                >
                <button
                    type="button"
                    class="pw-toggle"
                    :class="{ 'is-active': show }"
                    @click="show = !show"
                    :aria-pressed="show.toString()"
                    aria-label="Mostrar u ocultar contrase&ntilde;a"
                    x-cloak
                >
                    <svg
                        x-show="!show"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        style="position:absolute;"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    >
                        <path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z"/>
                        <circle cx="12" cy="12" r="3.2"/>
                    </svg>
                    <svg
                        x-show="show"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-50"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-50"
                        style="position:absolute;"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    >
                        <path d="M3 3l18 18"/>
                        <path d="M10.6 5.14A10.7 10.7 0 0 1 12 5c7 0 10.5 7 10.5 7a13.3 13.3 0 0 1-3.15 4.06M6.6 6.6C3.4 8.42 1.5 12 1.5 12s3.5 7 10.5 7a9.9 9.9 0 0 0 5.1-1.35"/>
                        <path d="M9.5 9.9a3.2 3.2 0 0 0 4.6 4.5"/>
                    </svg>
                </button>
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
@endsection
