<x-app-layout title="Lavadoras - QuickWash Campus">
    <div class="mb-6">
        <h1 class="section-title">Lavadoras</h1>
        <p class="section-subtitle">Administra el estado operativo de las 10 máquinas del servicio.</p>
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
                <form class="mt-5 space-y-3" method="POST" action="{{ route('staff.washers.update', $washer) }}">@csrf @method('PATCH')
                    <select class="input" name="status">
                        @foreach(\App\Models\Washer::STATUSES as $status)<option value="{{ $status }}" @selected($washer->status === $status)>{{ str_replace('_', ' ', $status) }}</option>@endforeach
                    </select>
                    <button class="btn-secondary w-full">Actualizar</button>
                </form>
            </article>
        @endforeach
    </section>
</x-app-layout>
