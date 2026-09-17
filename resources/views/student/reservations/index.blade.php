<x-app-layout title="Mis reservas - QuickWash Campus">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="section-title">Mis reservas</h1>
            <p class="section-subtitle">Consulta tus turnos y cancela reservas pendientes antes de que comiencen.</p>
        </div>
        <a class="btn-primary" href="{{ route('student.reservations.create') }}">Nueva reserva</a>
    </div>
    <div class="table-wrap">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="table-head"><tr><th class="table-cell">Lavadora</th><th class="table-cell">Fecha</th><th class="table-cell">Hora</th><th class="table-cell">Prendas</th><th class="table-cell">Estado</th><th class="table-cell">Acciones</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reservations as $reservation)
                        <tr class="transition hover:bg-cyan-50/70"><td class="table-cell font-bold text-slate-950">{{ $reservation->washer->code }}</td><td class="table-cell">{{ $reservation->reservation_date->format('d/m/Y') }}</td><td class="table-cell">{{ $reservation->reservation_time }}</td><td class="table-cell">{{ $reservation->garments_count }}</td><td class="table-cell"><span class="badge {{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span></td><td class="table-cell">@if($reservation->status === 'pending' && ! $reservation->isPastSlot())<form method="POST" action="{{ route('student.reservations.cancel', $reservation) }}">@csrf @method('PATCH')<button class="btn-danger">Cancelar</button></form>@else<span class="text-slate-400">Sin acción</span>@endif</td></tr>
                    @empty
                        <tr><td class="table-cell text-slate-500" colspan="6">Aún no tienes reservas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 p-4">{{ $reservations->links() }}</div>
    </div>
</x-app-layout>
