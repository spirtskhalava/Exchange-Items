@extends('layouts.app')

@section('meta_title', 'Browse on Map — Bartaro')
@section('meta_description', 'Explore trade listings near you on an interactive map.')

@section('content')
<div style="height:calc(100vh - 64px);position:relative;">

    {{-- Map container --}}
    <div id="map" style="width:100%;height:100%;z-index:1;"></div>

    {{-- Floating counter --}}
    <div style="position:absolute;top:1rem;left:50%;transform:translateX(-50%);z-index:999;background:white;border-radius:2rem;padding:.45rem 1.1rem;box-shadow:0 4px 20px rgba(0,0,0,.13);font-size:.82rem;font-weight:600;color:#374151;pointer-events:none;">
        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
        <span id="map-count">{{ $products->count() }}</span> listings on map
    </div>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/leaflet.js') }}"></script>
<script>
(function () {
    const products = @json($products);

    const map = L.map('map', { zoomControl: true }).setView([30, 15], 2);

    // OpenStreetMap tiles (free, no API key)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Custom marker icon
    const markerIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:34px;height:34px;border-radius:50% 50% 50% 0;
            background:var(--p,#4f46e5);border:2px solid white;
            box-shadow:0 2px 8px rgba(0,0,0,.25);
            transform:rotate(-45deg);
            display:flex;align-items:center;justify-content:center;">
            <div style="transform:rotate(45deg);color:white;font-size:13px;">⇄</div>
        </div>`,
        iconSize: [34, 34],
        iconAnchor: [17, 34],
        popupAnchor: [0, -36],
    });

    const bounds = [];

    products.forEach(function (p) {
        if (!p.lat || !p.lng) return;

        bounds.push([p.lat, p.lng]);

        const thumb = p.thumb
            ? `<img src="${p.thumb}" alt="${p.name}" style="width:100%;height:110px;object-fit:cover;border-radius:.5rem .5rem 0 0;display:block;">`
            : '';

        const condColor = {
            'New': '#15803d', 'Like New': '#0369a1', 'Good': '#92400e', 'Fair': '#b45309', 'Poor': '#dc2626'
        }[p.condition] || '#6b7280';

        const popup = `
            <div style="width:200px;border-radius:.6rem;overflow:hidden;font-family:inherit;">
                ${thumb}
                <div style="padding:.65rem .75rem;">
                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;color:#6b7280;letter-spacing:.05em;margin-bottom:.2rem;">
                        ${p.category || ''}
                        <span style="color:${condColor};margin-left:.3rem;">• ${p.condition || ''}</span>
                    </div>
                    <div style="font-size:.88rem;font-weight:700;color:#111827;margin-bottom:.3rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p.name}</div>
                    <div style="font-size:.72rem;color:#6b7280;margin-bottom:.6rem;">
                        <i class="bi bi-geo-alt"></i> ${p.location}
                    </div>
                    <a href="${p.url}" style="display:block;text-align:center;background:#4f46e5;color:white;border-radius:.4rem;padding:.35rem .6rem;font-size:.78rem;font-weight:600;text-decoration:none;">
                        View listing →
                    </a>
                </div>
            </div>`;

        L.marker([p.lat, p.lng], { icon: markerIcon })
            .addTo(map)
            .bindPopup(popup, { maxWidth: 210, minWidth: 200 });
    });

    // Fit map to markers if any exist
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50], maxZoom: 10 });
    }
})();
</script>
@endpush
