<x-app-layout title="Registro - QuickWash Campus">
    <section class="mx-auto grid max-w-5xl overflow-hidden rounded-xl border border-white/70 bg-white shadow-xl shadow-slate-300/60 lg:grid-cols-[0.85fr_1.15fr]">
        <aside class="bg-slate-950 p-8 text-white sm:p-10">
            <div class="brand-mark bg-white text-slate-950">QW</div>
            <h1 class="mt-8 text-3xl font-black">Crea tu cuenta estudiante</h1>
            <p class="mt-4 text-sm leading-6 text-slate-300">Registra tus datos universitarios para reservar lavadoras, revisar tus turnos y cancelar reservas pendientes cuando corresponda.</p>
            <div class="mt-8 rounded-lg border border-white/10 bg-white/10 p-4 text-sm text-cyan-50">Máximo 3 reservas activas por estudiante.</div>
        </aside>
        <div class="p-6 sm:p-8 lg:p-10">
            <form class="grid gap-4 sm:grid-cols-2" method="POST" action="{{ route('register.store') }}">
                @csrf
                <label class="block text-sm font-bold text-slate-700">Nombre<input class="input mt-1" name="name" value="{{ old('name') }}" required></label>
                <label class="block text-sm font-bold text-slate-700">Apellido<input class="input mt-1" name="last_name" value="{{ old('last_name') }}" required></label>
                <label class="block text-sm font-bold text-slate-700">Código de estudiante<input class="input mt-1" name="student_code" value="{{ old('student_code') }}" required></label>
                <label class="block text-sm font-bold text-slate-700">Sede<input class="input mt-1" name="campus" value="{{ old('campus') }}" required></label>
                <label class="block text-sm font-bold text-slate-700 sm:col-span-2">Correo electrónico<input class="input mt-1" name="email" type="email" value="{{ old('email') }}" required></label>
                <label class="block text-sm font-bold text-slate-700">Contraseña<input class="input mt-1" name="password" type="password" required></label>
                <label class="block text-sm font-bold text-slate-700">Confirmar contraseña<input class="input mt-1" name="password_confirmation" type="password" required></label>
                <div class="sm:col-span-2 flex items-center justify-between gap-4 pt-2">
                    <a class="text-sm font-bold text-slate-600 hover:text-cyan-700" href="{{ route('login') }}">Ya tengo cuenta</a>
                    <button class="btn-primary">Crear cuenta</button>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
