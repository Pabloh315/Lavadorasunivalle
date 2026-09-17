<x-app-layout :title="$guard === 'staff' ? 'Login personal - QuickWash Campus' : 'Login - QuickWash Campus'">
    <section class="mx-auto grid max-w-6xl overflow-hidden rounded-xl border border-white/70 bg-white shadow-2xl shadow-slate-300/70 lg:grid-cols-[1.05fr_0.95fr]">
        <div class="relative overflow-hidden bg-slate-950 p-8 text-white sm:p-10 lg:p-12">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(34,211,238,0.35),transparent_18rem),radial-gradient(circle_at_80%_0%,rgba(16,185,129,0.22),transparent_16rem)]"></div>
            <div class="relative z-10 flex min-h-[34rem] flex-col justify-between">
                <div>
                    <div class="mb-10 inline-flex h-14 w-14 items-center justify-center rounded-lg bg-white text-lg font-black text-slate-950 shadow-lg">QW</div>
                    <p class="text-sm font-bold uppercase tracking-[0.22em] text-cyan-200">UNIVALLE Laundry</p>
                    <h1 class="mt-4 max-w-lg text-4xl font-black leading-tight sm:text-5xl">QuickWash Campus</h1>
                    <p class="mt-4 text-xl font-semibold text-cyan-50">Lava fácil. Reserva rápido.</p>
                    <p class="mt-6 max-w-md text-sm leading-6 text-slate-300">Reserva lavadoras por horario, consulta disponibilidad y administra el servicio de lavandería universitaria desde una sola pantalla.</p>
                </div>
                <div class="grid gap-3 text-sm sm:grid-cols-3">
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur"><strong class="block text-2xl">10</strong><span class="text-slate-300">lavadoras</span></div>
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur"><strong class="block text-2xl">12</strong><span class="text-slate-300">horarios</span></div>
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur"><strong class="block text-2xl">3</strong><span class="text-slate-300">reservas activas</span></div>
                </div>
            </div>
        </div>

        <div class="p-8 sm:p-10 lg:p-12">
            <div class="mb-8">
                <p class="text-sm font-bold uppercase tracking-wide text-cyan-700">Acceso al sistema</p>
                <h2 class="mt-2 text-3xl font-black text-slate-950">{{ $guard === 'staff' ? 'Ingreso del personal' : 'Ingreso de estudiantes' }}</h2>
                <p class="mt-2 text-sm text-slate-600">Usa tus credenciales institucionales o una cuenta de demostración.</p>
            </div>

            <div class="mb-6 rounded-xl border border-cyan-100 bg-cyan-50 p-4 text-sm text-slate-700">
                <p class="font-bold text-slate-950">Credenciales demo</p>
                @if($guard === 'staff')
                    <p class="mt-1">Correo: <span class="font-semibold">personal@quikwash.test</span></p>
                @else
                    <p class="mt-1">Correo: <span class="font-semibold">estudiante1@quikwash.test</span></p>
                @endif
                <p>Contraseña: <span class="font-semibold">Password123!</span></p>
            </div>

            <form class="space-y-4" method="POST" action="{{ route('login.store') }}">
                @csrf
                <label class="block text-sm font-bold text-slate-700">Correo electrónico<input class="input mt-1" name="email" type="email" value="{{ old('email') }}" required autofocus></label>
                <label class="block text-sm font-bold text-slate-700">Contraseña<input class="input mt-1" name="password" type="password" required></label>
                <label class="flex items-center gap-2 text-sm font-semibold text-slate-600"><input class="rounded border-slate-300 text-cyan-600" type="checkbox" name="remember"> Recordarme</label>
                <button class="btn-primary w-full">Entrar</button>
            </form>
            <div class="mt-6 flex flex-wrap gap-3 text-sm">
                <a class="font-bold text-cyan-700 hover:text-slate-950" href="{{ route('register') }}">Crear cuenta estudiante</a>
                <a class="font-bold text-slate-600 hover:text-slate-950" href="{{ $guard === 'staff' ? route('login') : route('staff.login') }}">{{ $guard === 'staff' ? 'Soy estudiante' : 'Soy personal' }}</a>
            </div>
        </div>
    </section>
</x-app-layout>
