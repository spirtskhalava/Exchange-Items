{{-- Usage: @include('_partials.verified-badge', ['user' => $user]) --}}
{{-- Optional: $size = 'sm' | 'md' (default sm) --}}
@if($user->isVerifiedTrader())
@php $size = $size ?? 'sm'; @endphp
@if($size === 'md')
<span class="verified-badge-md" title="Verified Trader — 10+ trades, 4.5★+">
    <i class="bi bi-patch-check-fill"></i> Verified Trader
</span>
@else
<span class="verified-badge-sm" title="Verified Trader — 10+ trades, 4.5★+">
    <i class="bi bi-patch-check-fill"></i>
</span>
@endif
@endif
