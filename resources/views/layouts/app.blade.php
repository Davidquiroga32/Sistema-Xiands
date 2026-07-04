<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') — @endif{{ config('app.name', 'XIANDS') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;600;700;900&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .top-bar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(8,8,8,0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0.85rem 1.25rem;
            display: flex; align-items: center; gap: 1rem;
        }

        .logo-sm {
            display: flex; align-items: center; gap: 0.6rem;
            text-decoration: none; flex-shrink: 0;
        }
        .logo-sm-icon {
            width: 30px; height: 30px;
            border-radius: 9px;
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            border: 1px solid var(--border-lit);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-size: 0.8rem; font-weight: 900;
            color: var(--silver-light);
            filter: drop-shadow(0 0 8px rgba(200,200,200,0.3));
        }
        .logo-sm span {
            font-family: 'Outfit', sans-serif; font-size: 1rem; font-weight: 700;
            letter-spacing: 0.15em;
            background: linear-gradient(135deg, #888, #e0e0e0, #aaa);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        .top-title {
            flex: 1;
            font-family: 'Outfit', sans-serif; font-size: 0.85rem;
            font-weight: 600; letter-spacing: 0.08em;
            color: var(--silver-light);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .top-actions { display: flex; gap: 0.5rem; }
        .icon-btn {
            width: 36px; height: 36px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: rgba(255,255,255,0.03);
            color: var(--silver);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 0.85rem; transition: all 0.2s;
            text-decoration: none; flex-shrink: 0;
        }
        .icon-btn:hover { border-color: var(--border-lit); color: var(--silver-light); background: rgba(255,255,255,0.06); }

        .back-btn {
            width: 34px; height: 34px; border-radius: 10px;
            border: 1px solid var(--border); background: transparent;
            color: var(--silver); display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1rem; transition: all 0.2s; text-decoration: none;
        }
        .back-btn:hover { border-color: var(--border-lit); color: var(--silver-light); }

        .main-content {
            padding-bottom: calc(var(--bottom-nav-h) + 1rem);
            padding-bottom: calc(var(--bottom-nav-h) + env(safe-area-inset-bottom) + 1rem);
        }

        .fab {
            position: fixed; bottom: calc(var(--bottom-nav-h) + 0.75rem); right: 1.25rem;
            width: 52px; height: 52px; border-radius: 16px;
            background: linear-gradient(135deg, #2a2a2a, #3d3d3d);
            border: 1px solid var(--border-lit);
            color: var(--white); font-size: 1.4rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 80;
            text-decoration: none;
            box-shadow: 0 8px 30px rgba(0,0,0,0.5), 0 0 20px rgba(150,150,150,0.08);
            transition: transform 0.15s, box-shadow 0.2s;
        }
        .fab:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,0,0,0.6), 0 0 30px rgba(150,150,150,0.12); }
        .fab:active { transform: scale(0.94); }

        .fade-in-card {
            animation: fadeUpCard 0.4s ease both;
        }
        @keyframes fadeUpCard {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media(min-width: 640px) {
            .main-content {
                max-width: 680px;
                margin: 0 auto;
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            .top-bar {
                padding-left: calc((100% - 680px)/2 + 2rem);
                padding-right: calc((100% - 680px)/2 + 2rem);
            }
        }
    </style>
</head>
<body>
    <header class="top-bar">
        <a href="{{ route('dashboard') }}" class="logo-sm">
            <div class="logo-sm-icon">X</div>
            <span>XIANDS</span>
        </a>
        <div class="top-title">@yield('page_title', '')</div>
        <div class="top-actions">
            <a href="{{ route('profile.edit') }}" class="icon-btn" title="Perfil">
                <svg style="width:1.1rem;height:1.1rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <circle cx="12" cy="10.2" r="3.2"/>
                    <path d="M6.2 19a6.2 6.2 0 0 1 11.6 0"/>
                </svg>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="icon-btn" title="Cerrar sesión" style="width:auto;padding:0 0.75rem;gap:0.4rem;display:flex;align-items:center;font-size:0.7rem;letter-spacing:0.05em;font-weight:500;">
                    Cerrar sesión
                    <svg style="width:0.9rem;height:0.9rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <x-bottom-nav />

    <x-toast />

    @livewireScripts

    <script>
        document.addEventListener('alpine:init', () => {});
    </script>
</body>
</html>
