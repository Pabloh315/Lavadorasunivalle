<x-app-layout title="Estudiantes - QuickWash Campus">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><h1 class="text-2xl font-bold">Estudiantes</h1><p class="text-sm text-slate-600">Busca por nombre, codigo, correo o sede.</p></div>
        <form class="flex gap-2" method="GET" action="{{ route('staff.students.index') }}"><input class="input" name="q" value="{{ request('q') }}" placeholder="Buscar"><button class="btn-secondary">Buscar</button></form>
    </div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"><div class="overflow-x-auto"><table class="min-w-full text-left text-sm">
        <thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr><th class="p-4">Nombre</th><th class="p-4">Codigo</th><th class="p-4">Sede</th><th class="p-4">Correo</th><th class="p-4">Reservas</th><th class="p-4">Creado</th></tr></thead>
        <tbody class="divide-y divide-slate-100">@forelse($students as $student)<tr><td class="p-4 font-medium">{{ $student->fullName() }}</td><td class="p-4">{{ $student->student_code }}</td><td class="p-4">{{ $student->campus }}</td><td class="p-4">{{ $student->email }}</td><td class="p-4">{{ $student->reservations_count }}</td><td class="p-4">{{ $student->created_at->format('d/m/Y') }}</td></tr>@empty<tr><td class="p-4 text-slate-500" colspan="6">No se encontraron estudiantes.</td></tr>@endforelse</tbody>
    </table></div><div class="border-t border-slate-100 p-4">{{ $students->links() }}</div></div>
</x-app-layout>
