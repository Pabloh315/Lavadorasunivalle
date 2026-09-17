<x-app-layout :title="$guard === 'staff' ? 'Login personal - QuickWash Campus' : 'Login - QuickWash Campus'">
    <section class="mx-auto grid max-w-5xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm md:grid-cols-2">
        <div class="bg-cyan-700 p-8 text-white md:p-10">
            <div class="mb-12 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-white text-lg font-black text-cyan-700">QW</div>
            <h1 class="text-3xl font-black tracking-normal">QuickWash Campus</h1>
            <p class="mt-3 text-lg text-cyan-50">Lava fácil. Reserva rápido.</p>
            <p class="mt-8 max-w-sm text-sm leading-6 text-cyan-50">Reserva lavadoras por horario, revisa el estado de tus lavados y administra la operacion universitaria desde una sola pantalla.</p>
        </div>
        <div class="p-8 md:p-10">
            <h2 class="text-2xl font-bold">{{ $guard === 'staff' ? 'Ingreso del personal' : 'Ingreso de estudiantes' }}</h2>
            <form class="mt-6 space-y-4" method="POST" action="{{ route('login.store') }}">
                @csrf
                <label class="block text-sm font-medium">Correo electronico<input class="input mt-1" name="email" type="email" value="{{ old('email') }}" required autofocus></label>
                <label class="block text-sm font-medium">Contraseña<input class="input mt-1" name="password" type="password" required></label>
                <label class="flex items-center gap-2 text-sm text-slate-600"><input class="rounded border-slate-300" type="checkbox" name="remember"> Recordarme</label>
                <button class="btn-primary w-full">Entrar</button>
            </form>
            <div class="mt-6 flex flex-wrap gap-3 text-sm">
                <a class="font-semibold text-cyan-700" href="{{ route('register') }}">Crear cuenta estudiante</a>
                <a class="font-semibold text-slate-600" href="{{ $guard === 'staff' ? route('login') : route('staff.login') }}">{{ $guard === 'staff' ? 'Soy estudiante' : 'Soy personal' }}</a>
            </div>
        </div>
    </section>
</x-app-layout>
