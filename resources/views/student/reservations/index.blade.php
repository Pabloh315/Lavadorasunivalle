<x-app-layout title="Mis reservas - QuikWash">
    <div class="mb-6 flex items-center justify-between gap-4"><h1 class="text-2xl font-bold">Mis reservas</h1><a class="btn-primary" href="{{ route('student.reservations.create') }}">Nueva reserva</a></div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto"><table class="min-w-full text-left text-sm">
            <thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr><th class="p-4">Lavadora</th><th class="p-4">Fecha</th><th class="p-4">Hora</th><th class="p-4">Kg</th><th class="p-4">Estado</th><th class="p-4">Acciones</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($reservations as $reservation)
                    <tr><td class="p-4 font-medium">{{ $reservation->washer->code }}</td><td class="p-4">{{ $reservation->reservation_date->format('d/m/Y') }}</td><td class="p-4">{{ $reservation->reservation_time }}</td><td class="p-4">{{ $reservation->weight_kg }}</td><td class="p-4"><span class="badge {{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span></td><td class="p-4">@if($reservation->status === 'pending')<form method="POST" action="{{ route('student.reservations.cancel', $reservation) }}">@csrf @method('PATCH')<button class="btn-danger">Cancelar</button></form>@else<span class="text-slate-400">Sin accion</span>@endif</td></tr>
                @empty
                    <tr><td class="p-4 text-slate-500" colspan="6">Aun no tienes reservas.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="border-t border-slate-100 p-4">{{ $reservations->links() }}</div>
    </div>
</x-app-layout>
