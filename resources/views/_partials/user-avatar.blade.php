@php
    $size   = $size ?? 40;
    $fsize  = $fsize ?? round($size * 0.38);
    $colors = ['#4f46e5','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
    $color  = $colors[crc32($user->name) % count($colors)];
@endphp
@if($user->avatar_url)
    <img src="{{ $user->avatar_url }}"
         alt="{{ $user->name }}"
         class="rounded-circle object-fit-cover flex-shrink-0 {{ $class ?? '' }}"
         style="width:{{ $size }}px;height:{{ $size }}px;">
@else
    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0 {{ $class ?? '' }}"
         style="width:{{ $size }}px;height:{{ $size }}px;font-size:{{ $fsize }}px;background:{{ $color }};letter-spacing:-.5px;">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
@endif
