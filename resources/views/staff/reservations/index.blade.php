<x-app-layout title="Reservas - QuickWash Campus">
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="section-title">Reservas</h1>
            <p class="section-subtitle">Filtra por estudiante, fecha o estado y actualiza el avance del servicio.</p>
        </div>
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
    <div class="table-wrap">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="table-head"><tr><th class="table-cell">Estudiante</th><th class="table-cell">Lavadora</th><th class="table-cell">Fecha</th><th class="table-cell">Prendas</th><th class="table-cell">Notas</th><th class="table-cell">Estado</th><th class="table-cell">Cambiar</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reservations as $reservation)
                        <tr class="transition hover:bg-cyan-50/70"><td class="table-cell font-semibold text-slate-950">{{ $reservation->user->fullName() }}</td><td class="table-cell font-bold">{{ $reservation->washer->code }}</td><td class="table-cell">{{ $reservation->reservation_date->format('d/m/Y') }} {{ $reservation->reservation_time }}</td><td class="table-cell">{{ $reservation->garments_count }}</td><td class="table-cell text-slate-600">{{ $reservation->notes ?: '-' }}</td><td class="table-cell"><span class="badge {{ $reservation->status }}">{{ __('labels.reservation_status.'.$reservation->status) }}</span></td><td class="table-cell">
                            @if(in_array($reservation->status, ['pending', 'in_progress'], true))
                                <form class="flex min-w-64 gap-2" method="POST" action="{{ route('staff.reservations.update', $reservation) }}">@csrf @method('PATCH')
                                    <select class="input min-w-36" name="status">@if($reservation->status === 'pending')<option value="in_progress">En proceso</option>@endif @if($reservation->status === 'in_progress')<option value="completed">Finalizado</option>@endif <option value="cancelled">Cancelado</option></select>
                                    <button class="btn-secondary">Guardar</button>
                                </form>
                            @else
                                <span class="text-slate-400">Cerrada</span>
                            @endif
                        </td></tr>
                    @empty
                        <tr><td class="table-cell text-slate-500" colspan="7">No hay reservas con esos filtros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 p-4">{{ $reservations->links() }}</div>
    </div>
</x-app-layout>
