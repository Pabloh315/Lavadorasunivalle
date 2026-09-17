<x-app-layout title="Perfil - QuickWash Campus">
    <section class="max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-2xl font-bold">Perfil</h1>
        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div><dt class="text-xs uppercase text-slate-500">Nombre</dt><dd class="font-semibold">{{ $user->fullName() }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Codigo</dt><dd class="font-semibold">{{ $user->student_code }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Sede</dt><dd class="font-semibold">{{ $user->campus }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Correo</dt><dd class="font-semibold">{{ $user->email }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Creado</dt><dd class="font-semibold">{{ $user->created_at->format('d/m/Y H:i') }}</dd></div>
        </dl>
    </section>
</x-app-layout>
