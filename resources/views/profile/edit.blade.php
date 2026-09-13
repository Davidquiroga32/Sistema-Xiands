@extends('layouts.app')

@section('title', 'Perfil')
@section('page_title', 'Perfil')

@section('content')
    <div style="padding:1rem 1.25rem 0.5rem;">
        <a href="{{ route('dashboard') }}" class="icon-btn" style="display:inline-flex;text-decoration:none;">
            &#8592;
        </a>
    </div>

    <div style="padding:0.5rem 1.25rem 2rem;">

        {{-- Profile Info --}}
        <div class="card-dark" style="padding:1.2rem;margin-bottom:1rem;">
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.2rem;">
                <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#222,#2e2e2e);border:1px solid var(--border-lit);display:flex;align-items:center;justify-content:center;font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:700;color:var(--silver-light);flex-shrink:0;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div style="flex:1;">
                    <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:600;color:var(--silver-bright);">{{ $user->name }}</div>
                    <div style="font-size:0.7rem;color:var(--silver-dark);margin-top:0.15rem;">{{ $user->email }}</div>
                    <div style="font-size:0.65rem;color:var(--silver-dark);margin-top:0.1rem;">Rol: {{ $user->roles->pluck('name')->map(fn($r) => ucfirst($r))->join(', ') }}</div>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="field-dark">
                    <label class="label-dark" for="name">Nombre</label>
                    <input type="text" id="name" name="name" class="input-dark" value="{{ old('name', $user->name) }}" required autocomplete="name">
                    @error('name')
                        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-dark">
                    <label class="label-dark" for="email">Correo electr&oacute;nico</label>
                    <input type="email" id="email" name="email" class="input-dark" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')
                        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary-dark" style="padding:0.75rem;font-size:0.82rem;">
                    Guardar cambios
                </button>

                @if (session('status') === 'profile-updated')
                    <p style="text-align:center;color:#7ab87a;font-size:0.75rem;margin-top:0.8rem;">Perfil actualizado.</p>
                @endif
            </form>
        </div>

        {{-- Update Password --}}
        <div class="card-dark" style="padding:1.2rem;margin-bottom:1rem;">
            <div style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.1em;color:var(--silver-bright);margin-bottom:1rem;">Cambiar contrase&ntilde;a</div>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="field-dark">
                    <label class="label-dark" for="current_password">Contrase&ntilde;a actual</label>
                    <input type="password" id="current_password" name="current_password" class="input-dark" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-dark">
                    <label class="label-dark" for="password">Nueva contrase&ntilde;a</label>
                    <input type="password" id="password" name="password" class="input-dark" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-dark">
                    <label class="label-dark" for="password_confirmation">Confirmar contrase&ntilde;a</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="input-dark" autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary-dark" style="padding:0.75rem;font-size:0.82rem;">
                    Actualizar contrase&ntilde;a
                </button>

                @if (session('status') === 'password-updated')
                    <p style="text-align:center;color:#7ab87a;font-size:0.75rem;margin-top:0.8rem;">Contrase&ntilde;a actualizada.</p>
                @endif
            </form>
        </div>

        {{-- Gestión de usuarios (solo administradora) --}}
        @if (Auth::user()->hasRole('administradora'))
            <a href="{{ route('users.index') }}" class="card-dark card-dark-hover" style="padding:1.2rem;margin-bottom:1rem;text-decoration:none;display:block;">
                <div style="display:flex;align-items:center;gap:0.9rem;">
                    <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#222,#2e2e2e);border:1px solid var(--border-lit);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.1rem;height:1.1rem;color:var(--silver-light);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M19 8v6M22 11h-6"/>
                        </svg>
                    </div>
                    <div style="flex:1;">
                        <div style="font-family:'Outfit',sans-serif;font-size:0.85rem;font-weight:600;color:var(--silver-bright);">Gesti&oacute;n de usuarios</div>
                        <div style="font-size:0.68rem;color:var(--silver-dark);margin-top:0.15rem;">Crear y administrar cuentas de administradora y secretaria</div>
                    </div>
                    <span style="color:var(--silver-dark);">&#8250;</span>
                </div>
            </a>
        @endif

        {{-- Delete Account --}}
        <div class="card-dark" style="padding:1.2rem;border-color:rgba(220,38,38,0.2);" x-data="{ open: false }">
            <div style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.1em;color:#f87171;margin-bottom:0.5rem;">Eliminar cuenta</div>
            <p style="font-size:0.72rem;color:var(--silver-dark);margin-bottom:1rem;line-height:1.5;">
                Una vez eliminada, todos tus datos ser&aacute;n borrados permanentemente.
            </p>

            <button type="button" @click="open = true" style="padding:0.65rem 1.2rem;border-radius:10px;border:1px solid rgba(220,38,38,0.3);background:transparent;color:#f87171;font-family:'Outfit',sans-serif;font-size:0.72rem;font-weight:600;letter-spacing:0.08em;cursor:pointer;transition:all 0.2s;">
                Eliminar cuenta
            </button>

            <div x-show="open" x-cloak @click.self="open = false" @keydown.escape.window="open = false"
                 style="position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.8);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;">
                <div style="width:90%;max-width:380px;background:var(--card2);border:1px solid var(--border);border-radius:20px;padding:1.5rem;">
                    <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:600;color:var(--silver-bright);margin-bottom:0.8rem;">Confirmar eliminaci&oacute;n</div>
                    <p style="font-size:0.75rem;color:var(--silver-dark);margin-bottom:1.2rem;line-height:1.5;">
                        Ingresa tu contrase&ntilde;a para confirmar la eliminaci&oacute;n de tu cuenta.
                    </p>

                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')

                        <div class="field-dark">
                            <label class="label-dark" for="delete_password">Contrase&ntilde;a</label>
                            <input type="password" id="delete_password" name="password" class="input-dark" placeholder="Tu contrase&ntilde;a" required>
                            @error('password', 'userDeletion')
                                <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display:flex;gap:0.75rem;">
                            <button type="button" @click="open = false" class="btn-outline-dark">Cancelar</button>
                            <button type="submit" class="btn-solid-dark" style="color:#f87171;border-color:rgba(220,38,38,0.3);">
                                Eliminar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
