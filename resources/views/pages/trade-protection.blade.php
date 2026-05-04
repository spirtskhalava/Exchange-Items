@extends('layouts.app')

@section('meta_title', 'Trade Protection — Safe Item Exchanges | Bartaro')
@section('meta_description', 'Learn how Bartaro keeps your trades safe. Optional trade insurance, dispute resolution, verified traders, and PayPal-secured payments protect every exchange.')
@section('meta_canonical', route('trade.protection'))

@section('content')

{{-- Hero --}}
<div style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 50%,#1e3a5f 100%);padding:4rem 0 3rem;">
    <div class="container text-center" style="max-width:640px;">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
             style="width:64px;height:64px;background:rgba(255,255,255,.1);backdrop-filter:blur(8px);">
            <i class="bi bi-shield-fill-check" style="font-size:1.8rem;color:#a5b4fc;"></i>
        </div>
        <h1 class="fw-bold text-white mb-2" style="font-size:2rem;letter-spacing:-.03em;">Bartaro Trade Protection</h1>
        <p style="color:#c7d2fe;font-size:1rem;line-height:1.7;">
            Every trade on Bartaro is backed by our protection program. Here's exactly what we cover, how disputes work, and what to do if something goes wrong.
        </p>
    </div>
</div>

<div class="container py-5" style="max-width:800px;">

    {{-- What's covered --}}
    <div class="mb-5">
        <div class="section-label mb-1">Coverage</div>
        <h2 class="section-title mb-4">What's Covered</h2>

        <div class="row g-3">
            @foreach([
                ['bi-arrow-left-right', '#4f46e5', '#eef2ff', 'Item-for-Item Trades', 'When you trade a physical item with another Bartaro user, the exchange is logged on-chain and both parties must confirm receipt.'],
                ['bi-cash-coin',        '#059669', '#f0fdf4', 'Cash Top-Up Transfers', 'Any cash top-up agreed as part of a trade is recorded. Disputes over unpaid top-ups are eligible for mediation.'],
                ['bi-box-seam',         '#d97706', '#fffbeb', 'Item Not As Described', 'If an item arrives in significantly worse condition than listed, you can open a dispute within 48 hours of delivery confirmation.'],
                ['bi-person-x',         '#dc2626', '#fef2f2', 'Non-Delivery',          "If the other party confirms the trade but never ships, you're covered. Our team will investigate and take action against bad actors."],
            ] as [$icon, $color, $bg, $title, $desc])
            <div class="col-md-6">
                <div class="card h-100 p-3" style="border-radius:var(--radius-lg);">
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-2"
                             style="width:40px;height:40px;background:{{ $bg }};">
                            <i class="bi {{ $icon }}" style="color:{{ $color }};font-size:1.1rem;"></i>
                        </div>
                        <div>
                            <div class="fw-bold mb-1" style="font-size:.9rem;">{{ $title }}</div>
                            <p class="text-muted mb-0" style="font-size:.82rem;line-height:1.6;">{{ $desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- How disputes work — timeline --}}
    <div class="mb-5">
        <div class="section-label mb-1">Process</div>
        <h2 class="section-title mb-4">How a Dispute Works</h2>

        <div class="dispute-timeline">
            @foreach([
                ['1', '#4f46e5', 'Open a Dispute',       'Within 48 hours of a problem, go to your trade in Offers and click "Open Dispute". Describe the issue and attach any evidence (photos, messages).'],
                ['2', '#0ea5e9', 'Both Parties Respond',  'The other party has 48 hours to respond. Bartaro reviews both sides and all evidence provided. Both can submit additional proof.'],
                ['3', '#d97706', 'Mediation',              "If agreement isn't reached, our team steps in as mediator. We may request more info and will issue a ruling within 72 hours."],
                ['4', '#059669', 'Resolution',             'The ruling is final. We may block or suspend accounts that acted in bad faith. Completed trade history is updated to reflect the outcome.'],
            ] as [$num, $color, $title, $desc])
            <div class="d-flex gap-4 mb-4">
                <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                     style="width:40px;height:40px;background:{{ $color }};font-size:.9rem;flex-shrink:0;">
                    {{ $num }}
                </div>
                <div class="pt-1">
                    <div class="fw-bold mb-1" style="font-size:.9rem;">{{ $title }}</div>
                    <p class="text-muted mb-0" style="font-size:.84rem;line-height:1.6;">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Not covered --}}
    <div class="mb-5">
        <div class="section-label mb-1">Exclusions</div>
        <h2 class="section-title mb-4">What's Not Covered</h2>
        <div class="card p-4" style="border-radius:var(--radius-lg);border-left:4px solid #ef4444;">
            <ul class="mb-0 d-flex flex-column gap-2" style="list-style:none;padding:0;">
                @foreach([
                    'Trades agreed and completed off-platform (outside Bartaro)',
                    'Change of mind after both parties confirm receipt',
                    'Normal wear or minor cosmetic differences not affecting function',
                    'Disputes opened more than 48 hours after delivery confirmation',
                    'Accounts with a history of abuse or fraudulent dispute submissions',
                ] as $item)
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-x-circle-fill text-danger flex-shrink-0 mt-1" style="font-size:.85rem;"></i>
                    <span style="font-size:.84rem;color:var(--text2);">{{ $item }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- CTA --}}
    <div class="card text-center p-5" style="border-radius:var(--radius-lg);background:linear-gradient(135deg,#eef2ff,#e0f2fe);">
        <i class="bi bi-shield-check" style="font-size:2.5rem;color:var(--p);display:block;margin-bottom:1rem;"></i>
        <h4 class="fw-bold mb-2">Ready to trade safely?</h4>
        <p class="text-muted mb-4" style="font-size:.9rem;">Browse thousands of items and make your first trade with full protection.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('products.index') }}" class="btn btn-primary px-4" style="border-radius:.65rem;">
                <i class="bi bi-grid me-1"></i> Browse Items
            </a>
            @auth
            <a href="{{ route('trades.index') }}" class="btn btn-light px-4" style="border-radius:.65rem;">
                <i class="bi bi-clock-history me-1"></i> My Trade History
            </a>
            @endauth
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.section-label { font-size:.72rem; font-weight:700; color:var(--p); text-transform:uppercase; letter-spacing:.08em; }
.section-title  { font-size:1.35rem; font-weight:800; color:var(--text); letter-spacing:-.025em; margin:0; }
.dispute-timeline { padding-left:.5rem; }
</style>
@endpush
