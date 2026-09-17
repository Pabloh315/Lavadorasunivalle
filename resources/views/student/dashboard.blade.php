<x-app-layout title="Dashboard estudiante - QuickWash Campus">
    <section class="mb-8 overflow-hidden rounded-xl bg-slate-950 p-6 text-white shadow-xl shadow-slate-300/60 sm:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-cyan-200">Panel estudiante</p>
                <h1 class="mt-3 text-3xl font-black sm:text-4xl">Hola, {{ auth()->user()->name }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">Gestiona tus reservas de lavandería universitaria, revisa disponibilidad y mantén tus lavados organizados durante la semana.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row lg:justify-end">
                <a class="btn-primary bg-cyan-500 text-slate-950 hover:bg-cyan-300" href="{{ route('student.reservations.create') }}">Nueva reserva</a>
                <a class="btn-secondary border-white/20 bg-white/10 text-white hover:bg-white hover:text-slate-950" href="{{ route('student.reservations.index') }}">Mis reservas</a>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-4">
        <div class="stat"><span>Activas</span><strong>{{ $activeCount }}</strong></div>
        <div class="stat"><span>Pendientes</span><strong>{{ $pendingCount }}</strong></div>
        <div class="stat"><span>Completadas</span><strong>{{ $completedCount }}</strong></div>
        <div class="stat"><span>Próxima</span><strong class="text-base leading-7">{{ $nextReservation ? $nextReservation->reservation_date->format('d/m').' '.$nextReservation->reservation_time : 'Sin reserva' }}</strong></div>
    </section>

    <div class="mt-10 mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="section-title">Disponibilidad de lavadoras</h2>
            <p class="section-subtitle">Estado operativo y reservas activas por máquina.</p>
        </div>
    </div>
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach($washers as $washer)
            <article class="panel-soft p-4 transition hover:-translate-y-1 hover:border-cyan-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <strong class="block text-lg text-slate-950">{{ $washer->code }}</strong>
                        <p class="text-sm text-slate-600">{{ $washer->name }}</p>
                    </div>
                    <span class="badge {{ $washer->status }}">{{ str_replace('_', ' ', $washer->status) }}</span>
                </div>
                <div class="mt-5 rounded-lg bg-slate-50 p-3 text-xs font-semibold text-slate-500">Reservas activas: <span class="text-slate-950">{{ $washer->active_reservations_count }}</span></div>
            </article>
        @endforeach
    </section>
</x-app-layout>
