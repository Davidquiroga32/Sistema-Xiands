<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'XIANDS') }} — Acceso</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;600;700;900&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .logo-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2.5rem;
            animation: fadeDown 0.8s ease both;
        }

        .logo-mark {
            width: 88px;
            height: 88px;
            border-radius: 24px;
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            border: 1px solid var(--border-lit);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            color: var(--silver-light);
            filter: drop-shadow(0 0 20px rgba(200,200,200,0.25)) drop-shadow(0 0 60px rgba(150,150,150,0.1));
            animation: shimmerLogo 3s ease-in-out infinite;
        }

        @keyframes shimmerLogo {
            0%, 100% { filter: drop-shadow(0 0 20px rgba(200,200,200,0.2)) drop-shadow(0 0 50px rgba(150,150,150,0.08)); }
            50% { filter: drop-shadow(0 0 30px rgba(220,220,220,0.4)) drop-shadow(0 0 80px rgba(180,180,180,0.15)); }
        }

        .logo-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 0.25em;
            background: linear-gradient(135deg, #888 0%, #e0e0e0 40%, #aaa 60%, #e8e8e8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-tagline {
            font-size: 0.65rem;
            letter-spacing: 0.3em;
            color: var(--silver-dark);
            text-transform: uppercase;
            font-weight: 300;
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card-animated {
            animation: fadeUp 0.8s 0.2s ease both;
        }

        .version-badge-animated {
            animation: fadeUp 0.8s 0.4s ease both;
        }

        .card-shimmer-line {
            position: absolute;
            top: 0; left: 10%; right: 10%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(200,200,200,0.3), transparent);
            border-radius: 1px;
        }

        @media (min-width: 480px) {
            .logo-mark { width: 110px; height: 110px; }
            .logo-name { font-size: 2.2rem; }
        }
    </style>
</head>
<body class="overflow-hidden">
    <div class="ambient-bg"></div>
    <div class="grid-bg"></div>

    <div class="relative z-10 min-h-screen flex flex-col items-center justify-center px-6 py-8">
        <div class="logo-block">
            <div class="logo-mark">X</div>
            <div class="logo-name">XIANDS</div>
            <div class="logo-tagline">Sistema de Gesti&oacute;n</div>
        </div>

        <div class="w-full max-w-[400px] glass-card p-8 sm:p-10 relative mx-auto login-card-animated">
            <div class="card-shimmer-line"></div>
            @yield('content')
        </div>

        <div class="version-badge-animated mt-8 text-[0.6rem] text-[--border-lit] tracking-[0.2em] text-center">
            XIANDS v2.4 &nbsp;&middot;&nbsp; SISTEMA SEGURO
        </div>
    </div>

    @livewireScripts
</body>
</html>
