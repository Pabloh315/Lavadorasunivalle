<x-app-layout title="Nueva reserva - QuikWash">
    <section class="mx-auto max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-2xl font-bold">Nueva reserva</h1>
        <form class="mt-6 grid gap-4 sm:grid-cols-2" method="POST" action="{{ route('student.reservations.store') }}" data-availability-url="{{ route('student.reservations.available-hours') }}">
            @csrf
            <label class="block text-sm font-medium sm:col-span-2">Lavadora
                <select class="input mt-1" name="washing_machine_id" id="washing_machine_id" required>
                    <option value="">Selecciona una lavadora</option>
                    @foreach($washers as $washer)<option value="{{ $washer->id }}" @selected(old('washing_machine_id') == $washer->id)>{{ $washer->code }} - {{ $washer->name }}</option>@endforeach
                </select>
            </label>
            <label class="block text-sm font-medium">Fecha<input class="input mt-1" id="reservation_date" name="reservation_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('reservation_date', $selectedDate) }}" required></label>
            <label class="block text-sm font-medium">Hora
                <select class="input mt-1" id="reservation_time" name="reservation_time" required>
                    @forelse($hours as $hour)<option value="{{ $hour }}" @selected(old('reservation_time') === $hour)>{{ $hour }}</option>@empty<option value="">Sin horarios disponibles</option>@endforelse
                </select>
            </label>
            <label class="block text-sm font-medium sm:col-span-2">Cantidad de ropa en kg<input class="input mt-1" name="weight_kg" type="number" step="0.1" min="0.1" max="30" value="{{ old('weight_kg', 5) }}" required></label>
            <label class="block text-sm font-medium sm:col-span-2">Notas opcionales<textarea class="input mt-1" name="notes" rows="3" maxlength="500">{{ old('notes') }}</textarea></label>
            <div class="sm:col-span-2 flex justify-end"><button class="btn-primary">Reservar</button></div>
        </form>
    </section>
</x-app-layout>
