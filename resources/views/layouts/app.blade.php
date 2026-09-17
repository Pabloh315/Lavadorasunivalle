<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'QuickWash Campus' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="page-shell">
    <div class="page-backdrop">
        @auth
            <header class="topbar">
                <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:px-8 md:flex-row md:items-center md:justify-between">
                    <a href="{{ auth()->user()->isLaundryStaff() ? route('staff.dashboard') : route('student.dashboard') }}" class="flex items-center gap-3">
                        <span class="brand-mark">QW</span>
                        <span>
                            <span class="block text-lg font-black leading-5 text-slate-950">QuickWash Campus</span>
                            <span class="block text-xs font-semibold text-cyan-700">Lava fácil. Reserva rápido.</span>
                        </span>
                    </a>
                    <nav class="flex flex-wrap items-center gap-2 text-sm">
                        @if(auth()->user()->isLaundryStaff())
                            <a class="nav-link" href="{{ route('staff.dashboard') }}">Dashboard</a>
                            <a class="nav-link" href="{{ route('staff.reservations.index') }}">Reservas</a>
                            <a class="nav-link" href="{{ route('staff.washers.index') }}">Lavadoras</a>
                            <a class="nav-link" href="{{ route('staff.students.index') }}">Estudiantes</a>
                        @else
                            <a class="nav-link" href="{{ route('student.dashboard') }}">Dashboard</a>
                            <a class="nav-link" href="{{ route('student.reservations.create') }}">Nueva reserva</a>
                            <a class="nav-link" href="{{ route('student.reservations.index') }}">Mis reservas</a>
                            <a class="nav-link" href="{{ route('student.profile') }}">Perfil</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn-secondary">Salir</button></form>
                    </nav>
                </div>
            </header>
        @endauth

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 shadow-sm">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800 shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
            {{ $slot }}
        </main>
    </div>
</body>
</html>
