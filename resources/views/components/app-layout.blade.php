<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'QuikWash' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen">
        @auth
            <header class="border-b border-slate-200 bg-white">
                <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:px-8 md:flex-row md:items-center md:justify-between">
                    <a href="{{ auth()->user()->isLaundryStaff() ? route('staff.dashboard') : route('student.dashboard') }}" class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-lg bg-cyan-600 text-sm font-black text-white">QW</span>
                        <span>
                            <span class="block text-lg font-bold leading-5">QuikWash</span>
                            <span class="block text-xs text-slate-500">Lava fácil. Reserva rápido.</span>
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
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
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
