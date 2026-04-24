@auth
@php
    $pendingVerification = auth()->user()
        ->notifications()
        ->whereNull('read_at')
        ->where('type', 'App\Notifications\ProductVerificationRequest')
        ->first();
@endphp

@if($pendingVerification)
<!-- Backdrop -->
<div id="verification-backdrop" style="
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.6);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
">
    <!-- Modal Box -->
    <div style="
        background: #fff;
        border-radius: 16px;
        padding: 32px;
        max-width: 480px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        text-align: center;
        font-family: sans-serif;
    ">
        <div style="font-size: 40px; margin-bottom: 12px;">🔍</div>
        <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 10px; color: #1a1a1a;">
            Product Verification Request
        </h2>
        <p style="font-size: 15px; color: #555; margin-bottom: 12px;">
    Is the product <strong style="color: #1a1a1a;">{{ $pendingVerification->data['product_name'] }}</strong> real and legitimate?
        </p>

<a href="{{ $pendingVerification->data['product_url'] ?? route('products.show', $pendingVerification->data['product_id']) }}" target="_blank" style="
    display: inline-block;
    margin-bottom: 20px;
    font-size: 14px;
    color: #3b82f6;
    text-decoration: underline;
">View Product →</a>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <button onclick="submitVerdict('real')" style="
                flex: 1;
                background: #22c55e;
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 8px;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
            ">✅ Yes, it's real</button>
            <button onclick="submitVerdict('fake')" style="
                flex: 1;
                background: #ef4444;
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 8px;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
            ">❌ No, it's fake</button>
        </div>
    </div>
</div>

<script>
function submitVerdict(verdict) {
    const url = '/products/{{ $pendingVerification->data['product_id'] }}/verify';
    const token = '{{ csrf_token() }}';
    const notificationId = '{{ $pendingVerification->id }}';

    // Disable buttons to prevent double clicking
    document.querySelectorAll('#verification-backdrop button').forEach(b => b.disabled = true);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ verdict })
    })
    .then(res => res.json())
    .then(data => {
        console.log('Response:', data);

        // Mark notification as read FIRST, then remove popup
        return fetch('/notifications/' + notificationId + '/read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token }
        });
    })
    .then(() => {
        // Only remove popup after notification is marked as read
        document.getElementById('verification-backdrop').remove();
    })
    .catch(err => console.error('Verification error:', err));
}
</script>
@endif
@endauth