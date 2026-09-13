@extends('layouts.app')

@section('title', 'Usuarios')
@section('page_title', 'Usuarios')

@section('content')
    <div style="padding:1rem 1.25rem 0.5rem;">
        <a href="{{ route('profile.edit') }}" class="icon-btn" style="display:inline-flex;text-decoration:none;">
            &#8592;
        </a>
    </div>

    <div style="padding:0.5rem 1.25rem 2rem;" x-data="{ editing: null }">

        {{-- Crear usuario --}}
        <div class="card-dark" style="padding:1.2rem;margin-bottom:1rem;">
            <div style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.1em;color:var(--silver-bright);margin-bottom:1rem;">Crear usuario</div>

            <form method="post" action="{{ route('users.store') }}">
                @csrf

                <div class="field-dark">
                    <label class="label-dark" for="name">Nombre</label>
                    <input type="text" id="name" name="name" class="input-dark" value="{{ old('name') }}" required autocomplete="off">
                    @error('name')
                        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-dark">
                    <label class="label-dark" for="email">Correo electr&oacute;nico</label>
                    <input type="email" id="email" name="email" class="input-dark" value="{{ old('email') }}" required autocomplete="off">
                    @error('email')
                        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-dark">
                    <label class="label-dark" for="role">Rol</label>
                    <select id="role" name="role" class="input-dark" required>
                        <option value="secretaria" {{ old('role') === 'secretaria' ? 'selected' : '' }}>Secretaria</option>
                        <option value="administradora" {{ old('role') === 'administradora' ? 'selected' : '' }}>Administradora</option>
                    </select>
                    @error('role')
                        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-dark">
                    <label class="label-dark" for="password">Contrase&ntilde;a</label>
                    <input type="password" id="password" name="password" class="input-dark" required autocomplete="new-password">
                    @error('password')
                        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-dark">
                    <label class="label-dark" for="password_confirmation">Confirmar contrase&ntilde;a</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="input-dark" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn-primary-dark" style="padding:0.75rem;font-size:0.82rem;">
                    Crear usuario
                </button>
            </form>
        </div>

        {{-- Lista de usuarios --}}
        <div class="card-dark" style="padding:1.2rem;">
            <div style="font-family:'Outfit',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.1em;color:var(--silver-bright);margin-bottom:1rem;">
                Usuarios registrados
                <span style="color:var(--silver-dark);font-weight:400;margin-left:0.5rem;">({{ $users->count() }})</span>
            </div>

            <div style="display:flex;flex-direction:column;gap:0.6rem;">
                @forelse ($users as $user)
                    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;border:1px solid var(--border);border-radius:12px;background:var(--card2);">
                        <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#222,#2e2e2e);border:1px solid var(--border-lit);display:flex;align-items:center;justify-content:center;font-family:'Outfit',sans-serif;font-weight:700;color:var(--silver-light);flex-shrink:0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:0.85rem;font-weight:600;color:var(--silver-bright);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
                            <div style="font-size:0.68rem;color:var(--silver-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->email }}</div>
                        </div>
                        <span style="font-size:0.6rem;padding:0.25rem 0.6rem;border-radius:8px;letter-spacing:0.05em;text-transform:uppercase;font-weight:600;
                            {{ $user->hasRole('administradora') ? 'background:rgba(200,180,120,0.12);color:#d8c88a;border:1px solid rgba(200,180,120,0.3);' : 'background:rgba(120,140,180,0.12);color:#a8b8d8;border:1px solid rgba(120,140,180,0.3);' }}">
                            {{ $user->hasRole('administradora') ? 'Admin' : 'Secretaria' }}
                        </span>
                        <div style="display:flex;gap:0.4rem;flex-shrink:0;">
                            <button type="button"
                                    @click="editing = @js(['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->roles->first()->name ?? 'secretaria'])"
                                    class="icon-btn" title="Editar" style="width:32px;height:32px;font-size:0.75rem;">&#9998;</button>
                            @if ($user->id !== Auth::id())
                                <form method="post" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('¿Eliminar a {{ $user->name }}?');">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="icon-btn" title="Eliminar" style="width:32px;height:32px;font-size:0.75rem;color:#f87171;">&#10005;</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <p style="font-size:0.75rem;color:var(--silver-dark);text-align:center;padding:1rem;">No hay usuarios registrados.</p>
                @endforelse
            </div>
        </div>

        {{-- Modal editar --}}
        <div x-show="editing !== null" x-cloak @click.self="editing = null" @keydown.escape.window="editing = null"
             style="position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.8);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;">
            <div style="width:90%;max-width:400px;background:var(--card2);border:1px solid var(--border);border-radius:20px;padding:1.5rem;max-height:90vh;overflow-y:auto;">
                <div style="font-family:'Outfit',sans-serif;font-size:1rem;font-weight:600;color:var(--silver-bright);margin-bottom:1rem;">Editar usuario</div>

                <form method="post" :action="'/usuarios/' + editing.id" @submit="editing = null">
                    @csrf
                    @method('patch')

                    <div class="field-dark">
                        <label class="label-dark" for="edit_name">Nombre</label>
                        <input type="text" id="edit_name" name="name" class="input-dark" x-model="editing.name" required autocomplete="off">
                    </div>

                    <div class="field-dark">
                        <label class="label-dark" for="edit_email">Correo electr&oacute;nico</label>
                        <input type="email" id="edit_email" name="email" class="input-dark" x-model="editing.email" required autocomplete="off">
                    </div>

                    <div class="field-dark">
                        <label class="label-dark" for="edit_role">Rol</label>
                        <select id="edit_role" name="role" class="input-dark" x-model="editing.role" required>
                            <option value="secretaria">Secretaria</option>
                            <option value="administradora">Administradora</option>
                        </select>
                    </div>

                    <div class="field-dark">
                        <label class="label-dark" for="edit_password">Nueva contrase&ntilde;a (opcional)</label>
                        <input type="password" id="edit_password" name="password" class="input-dark" autocomplete="new-password">
                    </div>

                    <div class="field-dark">
                        <label class="label-dark" for="edit_password_confirmation">Confirmar contrase&ntilde;a</label>
                        <input type="password" id="edit_password_confirmation" name="password_confirmation" class="input-dark" autocomplete="new-password">
                    </div>

                    <div style="display:flex;gap:0.75rem;">
                        <button type="button" @click="editing = null" class="btn-outline-dark">Cancelar</button>
                        <button type="submit" class="btn-solid-dark">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
