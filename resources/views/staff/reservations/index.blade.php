<x-app-layout title="Reservas - QuickWash Campus">
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div><h1 class="text-2xl font-bold">Reservas</h1><p class="text-sm text-slate-600">Filtra por estudiante, fecha o estado.</p></div>
        <form class="grid gap-3 sm:grid-cols-4" method="GET" action="{{ route('staff.reservations.index') }}">
            <input class="input" name="student" value="{{ request('student') }}" placeholder="Estudiante">
            <input class="input" name="date" type="date" value="{{ request('date') }}">
            <select class="input" name="status">
                <option value="">Todos</option>
                @foreach(\App\Models\Reservation::STATUSES as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ __('labels.reservation_status.'.$status) }}</option>@endforeach
            </select>
            <button class="btn-secondary">Filtrar</button>
        </form>
    </div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"><div class="overflow-x-auto"><table class="min-w-full text-left text-sm">
        <thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr><th class="p-4">Estudiante</th><th class="p-4">Lavadora</th><th class="p-4">Fecha</th><th class="p-4">Prendas</th><th class="p-4">Notas</th><th class="p-4">Estado</th><th class="p-4">Cambiar</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($reservations as $reservation)
                <tr><td class="p-4">{{ $reservation->user->fullName() }}</td><td class="p-4">{{ $reservation->washer->code }}</td><td class="p-4">{{ $reservation->reservation_date->format('d/m/Y') }} {{ $reservation->reservation_time }}</td><td class="p-4">{{ $reservation->garments_count }}</td><td class="p-4 text-slate-600">{{ $reservation->notes ?: '-' }}</td><td class="p-4"><span class="badge {{ $reservation->status }}">{{ __('labels.reservation_status.'.$reservation->status) }}</span></td><td class="p-4">
                    @if(in_array($reservation->status, ['pending', 'in_progress'], true))
                        <form class="flex gap-2" method="POST" action="{{ route('staff.reservations.update', $reservation) }}">@csrf @method('PATCH')
                            <select class="input min-w-36" name="status">@if($reservation->status === 'pending')<option value="in_progress">En proceso</option>@endif @if($reservation->status === 'in_progress')<option value="completed">Finalizado</option>@endif <option value="cancelled">Cancelado</option></select>
                            <button class="btn-secondary">Guardar</button>
                        </form>
                    @else
                        <span class="text-slate-400">Cerrada</span>
                    @endif
                </td></tr>
            @empty
                <tr><td class="p-4 text-slate-500" colspan="7">No hay reservas con esos filtros.</td></tr>
            @endforelse
        </tbody>
    </table></div><div class="border-t border-slate-100 p-4">{{ $reservations->links() }}</div></div>
</x-app-layout>
