<x-app-layout title="Dashboard personal - QuickWash Campus">
    <div class="mb-8"><h1 class="text-3xl font-black">Panel del personal</h1><p class="text-slate-600">Seguimiento operativo de reservas y lavadoras.</p></div>
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="stat"><span>Reservas de hoy</span><strong>{{ $todayCount }}</strong></div>
        <div class="stat"><span>Pendientes</span><strong>{{ $pendingCount }}</strong></div>
        <div class="stat"><span>En proceso</span><strong>{{ $inProgressCount }}</strong></div>
        <div class="stat"><span>Finalizadas</span><strong>{{ $completedCount }}</strong></div>
        <div class="stat"><span>Canceladas</span><strong>{{ $cancelledCount }}</strong></div>
        <div class="stat"><span>Lavadoras disponibles</span><strong>{{ $availableWashers }}</strong></div>
        <div class="stat"><span>En mantenimiento</span><strong>{{ $maintenanceWashers }}</strong></div>
    </section>
    <h2 class="mt-10 text-xl font-bold">Proximas reservas</h2>
    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"><div class="overflow-x-auto"><table class="min-w-full text-left text-sm">
        <thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr><th class="p-4">Estudiante</th><th class="p-4">Lavadora</th><th class="p-4">Fecha</th><th class="p-4">Estado</th></tr></thead>
        <tbody class="divide-y divide-slate-100">@foreach($reservations as $reservation)<tr><td class="p-4">{{ $reservation->user->fullName() }}</td><td class="p-4">{{ $reservation->washer->code }}</td><td class="p-4">{{ $reservation->reservation_date->format('d/m/Y') }} {{ $reservation->reservation_time }}</td><td class="p-4"><span class="badge {{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span></td></tr>@endforeach</tbody>
    </table></div></div>
</x-app-layout>
