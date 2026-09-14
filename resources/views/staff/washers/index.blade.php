<x-app-layout title="Lavadoras - QuikWash">
    <h1 class="mb-6 text-2xl font-bold">Lavadoras</h1>
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach($washers as $washer)
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between"><strong>{{ $washer->code }}</strong><span class="badge {{ $washer->status }}">{{ str_replace('_', ' ', $washer->status) }}</span></div>
                <p class="mt-1 text-sm text-slate-600">{{ $washer->name }}</p>
                <form class="mt-4 space-y-3" method="POST" action="{{ route('staff.washers.update', $washer) }}">@csrf @method('PATCH')
                    <select class="input" name="status">
                        @foreach(\App\Models\Washer::STATUSES as $status)<option value="{{ $status }}" @selected($washer->status === $status)>{{ str_replace('_', ' ', $status) }}</option>@endforeach
                    </select>
                    <button class="btn-secondary w-full">Actualizar</button>
                </form>
            </article>
        @endforeach
    </section>
</x-app-layout>
