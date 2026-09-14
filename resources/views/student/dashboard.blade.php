<x-app-layout title="Dashboard estudiante - QuikWash">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div><h1 class="text-3xl font-black">Hola, {{ auth()->user()->name }}</h1><p class="text-slate-600">Gestiona tus reservas de lavanderia universitaria.</p></div>
        <a class="btn-primary" href="{{ route('student.reservations.create') }}">Nueva reserva</a>
    </div>
    <section class="grid gap-4 md:grid-cols-4">
        <div class="stat"><span>Activas</span><strong>{{ $activeCount }}</strong></div>
        <div class="stat"><span>Pendientes</span><strong>{{ $pendingCount }}</strong></div>
        <div class="stat"><span>Completadas</span><strong>{{ $completedCount }}</strong></div>
        <div class="stat"><span>Proxima</span><strong class="text-base">{{ $nextReservation ? $nextReservation->reservation_date->format('d/m').' '.$nextReservation->reservation_time : 'Sin reserva' }}</strong></div>
    </section>
    <h2 class="mt-10 text-xl font-bold">Disponibilidad de lavadoras</h2>
    <section class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach($washers as $washer)
            <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between"><strong>{{ $washer->code }}</strong><span class="badge {{ $washer->status }}">{{ str_replace('_', ' ', $washer->status) }}</span></div>
                <p class="mt-1 text-sm text-slate-600">{{ $washer->name }}</p>
                <p class="mt-4 text-xs text-slate-500">Reservas activas: {{ $washer->active_reservations_count }}</p>
            </article>
        @endforeach
    </section>
</x-app-layout>
