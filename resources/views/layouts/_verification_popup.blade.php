@auth
@php
    $pendingVerification = auth()->user()
        ->notifications()
        ->whereNull('read_at')
        ->where('type', 'App\Notifications\ProductVerificationRequest')
        ->first();
@endphp

@if($pendingVerification)
<div id="verification-backdrop" style="position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:99999;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(3px);">
    <div style="background:#fff;border-radius:1.25rem;padding:2rem;max-width:460px;width:90%;box-shadow:0 24px 64px rgba(0,0,0,.2);font-family:'Inter',sans-serif;text-align:center;">
        <div style="width:56px;height:56px;background:rgba(67,97,238,.08);border-radius:1rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#4361ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </div>
        <h2 style="font-size:1.1rem;font-weight:700;color:#1a1a2e;margin-bottom:.5rem;">Product Verification</h2>
        <p style="font-size:.875rem;color:#6b7280;margin-bottom:.5rem;line-height:1.6;">
            Is <strong style="color:#1a1a2e;">{{ $pendingVerification->data['product_name'] }}</strong> a real, legitimate item?
        </p>
        <a href="{{ $pendingVerification->data['product_url'] ?? route('products.show', $pendingVerification->data['product_id']) }}"
           target="_blank"
           style="display:inline-flex;align-items:center;gap:.3rem;font-size:.82rem;color:#4361ee;text-decoration:none;margin-bottom:1.5rem;">
            View listing <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
        <div style="display:flex;gap:.75rem;">
            <button onclick="submitVerdict('real')" style="flex:1;background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;border:none;padding:.75rem;border-radius:.75rem;font-size:.9rem;font-weight:600;cursor:pointer;transition:opacity .15s;" onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                ✓ Yes, it's real
            </button>
            <button onclick="submitVerdict('fake')" style="flex:1;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;padding:.75rem;border-radius:.75rem;font-size:.9rem;font-weight:600;cursor:pointer;transition:opacity .15s;" onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                ✕ No, it's fake
            </button>
        </div>
    </div>
</div>

<script>
function submitVerdict(verdict) {
    document.querySelectorAll('#verification-backdrop button').forEach(b => b.disabled = true);
    const token = '{{ csrf_token() }}';
    fetch('/products/{{ $pendingVerification->data['product_id'] }}/verify', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify({ verdict })
    })
    .then(r => r.json())
    .then(() => fetch('/notifications/{{ $pendingVerification->id }}/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': token } }))
    .then(() => document.getElementById('verification-backdrop').remove())
    .catch(err => console.error('Verification error:', err));
}
</script>
@endif
@endauth
