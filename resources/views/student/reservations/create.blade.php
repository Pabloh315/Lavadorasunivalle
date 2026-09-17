<x-app-layout title="Nueva reserva - QuickWash Campus">
    <section class="mx-auto grid max-w-5xl overflow-hidden rounded-xl border border-white/70 bg-white shadow-xl shadow-slate-300/60 lg:grid-cols-[0.8fr_1.2fr]">
        <aside class="bg-slate-950 p-8 text-white sm:p-10">
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-cyan-200">Reserva rápida</p>
            <h1 class="mt-3 text-3xl font-black">Nueva reserva</h1>
            <p class="mt-4 text-sm leading-6 text-slate-300">Selecciona lavadora, fecha y hora. El sistema bloquea horarios ocupados y evita reservas en máquinas no disponibles.</p>
            <div class="mt-8 space-y-3 text-sm">
                <div class="rounded-lg border border-white/10 bg-white/10 p-4">Horario disponible: 08:00 a 19:00</div>
                <div class="rounded-lg border border-white/10 bg-white/10 p-4">Máximo 3 reservas activas</div>
            </div>
        </aside>
        <div class="p-6 sm:p-8 lg:p-10">
            <form class="grid gap-4 sm:grid-cols-2" method="POST" action="{{ route('student.reservations.store') }}" data-availability-url="{{ route('student.reservations.available-hours') }}">
                @csrf
                <label class="block text-sm font-bold text-slate-700 sm:col-span-2">Lavadora
                    <select class="input mt-1" name="washing_machine_id" id="washing_machine_id" required>
                        <option value="">Selecciona una lavadora</option>
                        @foreach($washers as $washer)<option value="{{ $washer->id }}" @selected(old('washing_machine_id') == $washer->id)>{{ $washer->code }} - {{ $washer->name }}</option>@endforeach
                    </select>
                </label>
                <label class="block text-sm font-bold text-slate-700">Fecha<input class="input mt-1" id="reservation_date" name="reservation_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('reservation_date', $selectedDate) }}" required></label>
                <label class="block text-sm font-bold text-slate-700">Hora
                    <select class="input mt-1" id="reservation_time" name="reservation_time" required>
                        @forelse($hours as $hour)<option value="{{ $hour }}" @selected(old('reservation_time') === $hour)>{{ $hour }}</option>@empty<option value="">Sin horarios disponibles</option>@endforelse
                    </select>
                </label>
                <label class="block text-sm font-bold text-slate-700 sm:col-span-2">Cantidad de prendas<input class="input mt-1" name="garments_count" type="number" step="1" min="1" max="200" value="{{ old('garments_count', 8) }}" required></label>
                <label class="block text-sm font-bold text-slate-700 sm:col-span-2">Notas opcionales<textarea class="input mt-1" name="notes" rows="3" maxlength="500" placeholder="Ej. separar ropa clara">{{ old('notes') }}</textarea></label>
                <div class="sm:col-span-2 flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <a class="btn-secondary" href="{{ route('student.dashboard') }}">Volver</a>
                    <button class="btn-primary">Reservar</button>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
