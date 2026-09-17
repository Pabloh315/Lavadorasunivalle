<x-app-layout title="Dashboard personal - QuickWash Campus">
    <section class="mb-8 rounded-xl bg-slate-950 p-6 text-white shadow-xl shadow-slate-300/60 sm:p-8">
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-cyan-200">Operación QuickWash</p>
        <h1 class="mt-3 text-3xl font-black sm:text-4xl">Panel del personal</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">Supervisa reservas, cambios de estado y disponibilidad de lavadoras desde una vista operativa.</p>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="stat"><span>Reservas de hoy</span><strong>{{ $todayCount }}</strong></div>
        <div class="stat"><span>Pendientes</span><strong>{{ $pendingCount }}</strong></div>
        <div class="stat"><span>En proceso</span><strong>{{ $inProgressCount }}</strong></div>
        <div class="stat"><span>Finalizadas</span><strong>{{ $completedCount }}</strong></div>
        <div class="stat"><span>Canceladas</span><strong>{{ $cancelledCount }}</strong></div>
        <div class="stat"><span>Lavadoras disponibles</span><strong>{{ $availableWashers }}</strong></div>
        <div class="stat"><span>En mantenimiento</span><strong>{{ $maintenanceWashers }}</strong></div>
    </section>

    <div class="mt-10 mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="section-title">Próximas reservas</h2>
            <p class="section-subtitle">Turnos recientes para seguimiento rápido del servicio.</p>
        </div>
        <a class="btn-secondary" href="{{ route('staff.reservations.index') }}">Ver todas</a>
    </div>
    <div class="table-wrap">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="table-head"><tr><th class="table-cell">Estudiante</th><th class="table-cell">Lavadora</th><th class="table-cell">Fecha</th><th class="table-cell">Estado</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($reservations as $reservation)
                        <tr class="transition hover:bg-cyan-50/70"><td class="table-cell font-semibold text-slate-950">{{ $reservation->user->fullName() }}</td><td class="table-cell">{{ $reservation->washer->code }}</td><td class="table-cell">{{ $reservation->reservation_date->format('d/m/Y') }} {{ $reservation->reservation_time }}</td><td class="table-cell"><span class="badge {{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
