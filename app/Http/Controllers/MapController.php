<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    /** Map page — all visible products that have coordinates */
    public function index()
    {
        $products = Product::where('hide', 0)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select(['id', 'name', 'category', 'condition', 'location', 'latitude', 'longitude', 'image_paths'])
            ->latest()
            ->get()
            ->map(function ($p) {
                $paths  = json_decode($p->image_paths, true) ?? [];
                $thumb  = !empty($paths[0])
                    ? (str_starts_with($paths[0], 'http') ? $paths[0] : asset('storage/' . $paths[0]))
                    : null;
                return [
                    'id'        => $p->id,
                    'name'      => $p->name,
                    'category'  => $p->category,
                    'condition' => $p->condition,
                    'location'  => $p->location,
                    'lat'       => (float) $p->latitude,
                    'lng'       => (float) $p->longitude,
                    'thumb'     => $thumb,
                    'url'       => route('products.show', $p->id),
                ];
            });

        return view('map', compact('products'));
    }

    /**
     * Geocode a location string via Nominatim (cached 30 days).
     * Called by ProductController when a product is saved.
     */
    public static function geocode(string $location): ?array
    {
        if (blank($location)) return null;

        $cacheKey = 'geocode_' . md5(strtolower(trim($location)));

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($location) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'Bartaro/1.0 (bartaro.com)',
                ])->timeout(5)->get('https://nominatim.openstreetmap.org/search', [
                    'q'              => $location,
                    'format'         => 'json',
                    'limit'          => 1,
                    'addressdetails' => 0,
                ]);

                $data = $response->json();
                if (!empty($data[0]['lat'])) {
                    return [
                        'lat' => (float) $data[0]['lat'],
                        'lng' => (float) $data[0]['lon'],
                    ];
                }
            } catch (\Throwable) {}
            return null;
        });
    }
}
