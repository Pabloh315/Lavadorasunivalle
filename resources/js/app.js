document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-availability-url]');
    if (!form) return;

    const washer = form.querySelector('#washing_machine_id');
    const date = form.querySelector('#reservation_date');
    const time = form.querySelector('#reservation_time');

    const refreshHours = async () => {
        if (!washer.value || !date.value) return;

        const url = new URL(form.dataset.availabilityUrl, window.location.origin);
        url.searchParams.set('washing_machine_id', washer.value);
        url.searchParams.set('reservation_date', date.value);

        const response = await fetch(url, { headers: { Accept: 'application/json' } });
        if (!response.ok) return;

        const payload = await response.json();
        time.replaceChildren();

        if (!payload.hours.length) {
            time.append(new Option('Sin horarios disponibles', ''));
            return;
        }

        payload.hours.forEach((hour) => time.append(new Option(hour, hour)));
    };

    washer.addEventListener('change', refreshHours);
    date.addEventListener('change', refreshHours);
});
