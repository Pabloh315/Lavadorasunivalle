<x-app-layout title="Registro - QuikWash">
    <section class="mx-auto max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-2xl font-bold">Registro de estudiante</h1>
        <form class="mt-6 grid gap-4 sm:grid-cols-2" method="POST" action="{{ route('register.store') }}">
            @csrf
            <label class="block text-sm font-medium">Nombre<input class="input mt-1" name="name" value="{{ old('name') }}" required></label>
            <label class="block text-sm font-medium">Apellido<input class="input mt-1" name="last_name" value="{{ old('last_name') }}" required></label>
            <label class="block text-sm font-medium">Codigo de estudiante<input class="input mt-1" name="student_code" value="{{ old('student_code') }}" required></label>
            <label class="block text-sm font-medium">Sede<input class="input mt-1" name="campus" value="{{ old('campus') }}" required></label>
            <label class="block text-sm font-medium sm:col-span-2">Correo electronico<input class="input mt-1" name="email" type="email" value="{{ old('email') }}" required></label>
            <label class="block text-sm font-medium">Contrasena<input class="input mt-1" name="password" type="password" required></label>
            <label class="block text-sm font-medium">Confirmar contrasena<input class="input mt-1" name="password_confirmation" type="password" required></label>
            <div class="sm:col-span-2 flex items-center justify-between gap-4">
                <a class="text-sm font-semibold text-slate-600" href="{{ route('login') }}">Ya tengo cuenta</a>
                <button class="btn-primary">Crear cuenta</button>
            </div>
        </form>
    </section>
</x-app-layout>
