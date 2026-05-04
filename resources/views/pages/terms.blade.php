@extends('layouts.app')

@section('meta_title', 'Terms & Conditions — Bartaro')
@section('meta_description', 'Read the Bartaro Terms & Conditions. Understand your rights and responsibilities when listing items, making trades, and using our platform.')
@section('meta_canonical', route('terms'))

@push('styles')
<style>
.terms-hero {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff;
    padding: 4rem 0 3rem;
    margin-bottom: 0;
}
.terms-hero h1 { font-size: 2.2rem; font-weight: 800; letter-spacing: -.5px; }
.terms-hero p  { opacity: .85; font-size: 1rem; }

.terms-nav {
    position: sticky;
    top: 76px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.25rem;
}
.terms-nav a {
    display: block;
    font-size: .82rem;
    color: var(--muted);
    text-decoration: none;
    padding: .3rem .5rem;
    border-radius: .4rem;
    transition: all var(--transition);
}
.terms-nav a:hover,
.terms-nav a.active { background: var(--p-light); color: var(--p); font-weight: 600; }

.terms-section {
    scroll-margin-top: 90px;
    padding: 2.5rem 0;
    border-bottom: 1px solid var(--border);
}
.terms-section:last-child { border-bottom: none; }
.terms-section h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .6rem;
}
.terms-section h2 .num {
    width: 30px; height: 30px;
    background: var(--p-light);
    color: var(--p);
    border-radius: .5rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; font-weight: 700; flex-shrink: 0;
}
.terms-section p, .terms-section li {
    color: var(--text2);
    font-size: .92rem;
    line-height: 1.8;
}
.terms-section ul { padding-left: 1.25rem; }
.terms-section ul li { margin-bottom: .4rem; }

.terms-highlight {
    background: var(--p-light);
    border-left: 3px solid var(--p);
    border-radius: 0 .5rem .5rem 0;
    padding: 1rem 1.25rem;
    margin: 1rem 0;
    font-size: .88rem;
    color: var(--p-dark);
}
.terms-warn {
    background: #fef3c7;
    border-left: 3px solid #d97706;
    border-radius: 0 .5rem .5rem 0;
    padding: 1rem 1.25rem;
    margin: 1rem 0;
    font-size: .88rem;
    color: #92400e;
}
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="terms-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:.75rem;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div>
                <h1 class="mb-1">Terms &amp; Conditions</h1>
                <p class="mb-0">Last updated: May 2, 2026 &nbsp;·&nbsp; Effective immediately</p>
            </div>
        </div>
        <p style="max-width:620px;opacity:.8;font-size:.92rem;">
            Please read these terms carefully before using Bartaro. By accessing or using our platform you agree to be bound by these terms.
        </p>
    </div>
</div>

{{-- Body --}}
<div class="container py-5">
    <div class="row g-4">

        {{-- Sticky sidebar nav --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="terms-nav" id="termsSidebar">
                <p style="font-size:.7rem;font-weight:700;color:var(--muted2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem;">Contents</p>
                <a href="#acceptance"   >1. Acceptance of Terms</a>
                <a href="#platform"     >2. Platform Overview</a>
                <a href="#accounts"     >3. Accounts &amp; Eligibility</a>
                <a href="#listings"     >4. Listings &amp; Content</a>
                <a href="#trades"       >5. Trades &amp; Exchanges</a>
                <a href="#insurance"    >6. Trade Insurance &amp; Escrow</a>
                <a href="#payments"     >7. Payments &amp; Fees</a>
                <a href="#prohibited"   >8. Prohibited Conduct</a>
                <a href="#intellectual" >9. Intellectual Property</a>
                <a href="#privacy"      >10. Privacy</a>
                <a href="#disclaimers"  >11. Disclaimers</a>
                <a href="#limitation"   >12. Limitation of Liability</a>
                <a href="#termination"  >13. Termination</a>
                <a href="#contact"      >14. Contact</a>
            </div>
        </div>

        {{-- Main content --}}
        <div class="col-lg-9">

            <div class="terms-highlight">
                <strong><i class="bi bi-info-circle me-1"></i> Summary:</strong>
                Bartaro is a peer-to-peer item exchange platform. We connect traders but are not a party to any exchange. You are responsible for your listings, your conduct, and any items you trade.
            </div>

            {{-- 1 --}}
            <div class="terms-section" id="acceptance">
                <h2><span class="num">1</span> Acceptance of Terms</h2>
                <p>By creating an account, browsing listings, or using any part of the Bartaro platform ("Service"), you confirm that you have read, understood, and agree to these Terms &amp; Conditions and our Privacy Policy. If you do not agree, you must not use the Service.</p>
                <p>We may update these terms from time to time. Continued use after changes constitutes acceptance of the revised terms. We will notify registered users of material changes via email or in-app notification.</p>
            </div>

            {{-- 2 --}}
            <div class="terms-section" id="platform">
                <h2><span class="num">2</span> Platform Overview</h2>
                <p>Bartaro is an online marketplace that allows users to list personal items and propose exchanges (trades) with other users. We may also facilitate optional <strong>cash top-up offers</strong> and a voluntary <strong>Trade Insurance / Escrow</strong> service.</p>
                <p>Bartaro is a <strong>facilitator only</strong>. We are not a party to any exchange contract between users and accept no responsibility for the quality, safety, legality, or delivery of items traded.</p>
            </div>

            {{-- 3 --}}
            <div class="terms-section" id="accounts">
                <h2><span class="num">3</span> Accounts &amp; Eligibility</h2>
                <ul>
                    <li>You must be at least <strong>18 years old</strong> to create an account.</li>
                    <li>You must provide accurate, current, and complete registration information.</li>
                    <li>You are responsible for maintaining the confidentiality of your password and for all activities under your account.</li>
                    <li>One person may hold only one account. Duplicate accounts may be suspended.</li>
                    <li>Accounts created via Google OAuth are bound by these same terms.</li>
                    <li>We reserve the right to suspend or terminate accounts that violate these terms.</li>
                </ul>
            </div>

            {{-- 4 --}}
            <div class="terms-section" id="listings">
                <h2><span class="num">4</span> Listings &amp; Content</h2>
                <p>When you post a listing you represent and warrant that:</p>
                <ul>
                    <li>You own the item or have the legal right to trade it.</li>
                    <li>The item is accurately described — condition, photos, and any defects disclosed.</li>
                    <li>The item is not counterfeit, stolen, or prohibited (see Section 8).</li>
                    <li>Photos and descriptions are your original content or content you have the right to use.</li>
                </ul>
                <div class="terms-warn">
                    <strong><i class="bi bi-exclamation-triangle me-1"></i> Misrepresentation</strong> of item condition or ownership may result in immediate account suspension and may expose you to legal liability.
                </div>
                <p>Bartaro may remove any listing at its sole discretion without notice.</p>
            </div>

            {{-- 5 --}}
            <div class="terms-section" id="trades">
                <h2><span class="num">5</span> Trades &amp; Exchanges</h2>
                <p>A trade is formed when one user makes an offer and the other user explicitly accepts it through the platform. Both parties are then responsible for fulfilling the exchange in good faith.</p>
                <ul>
                    <li>Accepted trades should be completed within a <strong>reasonable time</strong> agreed by both parties.</li>
                    <li>Bartaro does not guarantee that any trade will be completed.</li>
                    <li>Cash top-up offers are optional additions to a trade and must be agreed by both parties.</li>
                    <li>Users are encouraged to use the Trade Insurance feature for added protection.</li>
                </ul>
            </div>

            {{-- 6 --}}
            <div class="terms-section" id="insurance">
                <h2><span class="num">6</span> Trade Insurance &amp; Escrow</h2>
                <p>Bartaro offers an optional <strong>Trade Insurance</strong> service for accepted exchanges. When both parties opt in:</p>
                <ul>
                    <li>Each party agrees a valuation for their item through a negotiation process.</li>
                    <li>Each party pays their item's agreed value plus a <strong>$5 service fee</strong> into escrow via PayPal.</li>
                    <li>Funds are held until both parties confirm receipt of their items.</li>
                    <li>If a dispute is raised, an admin will review evidence and determine an outcome.</li>
                    <li>Admin decisions on disputes are <strong>final</strong> within the platform.</li>
                </ul>
                <div class="terms-highlight">
                    <strong><i class="bi bi-shield-check me-1"></i> Note:</strong> Escrow payments are processed via PayPal. By using Trade Insurance you also agree to PayPal's terms of service. Bartaro is not a licensed financial institution or escrow agent.
                </div>
                <p>Bartaro reserves the right to modify, suspend, or discontinue the Trade Insurance feature at any time.</p>
            </div>

            {{-- 7 --}}
            <div class="terms-section" id="payments">
                <h2><span class="num">7</span> Payments &amp; Fees</h2>
                <ul>
                    <li>Listing items on Bartaro is <strong>free</strong>.</li>
                    <li>The Trade Insurance service carries a <strong>$5 fee per participant</strong> per trade.</li>
                    <li>PayPal transaction fees may apply and are governed by PayPal's own policies.</li>
                    <li>All payments are in <strong>USD</strong> unless otherwise stated.</li>
                    <li>Bartaro is not responsible for PayPal outages, payment failures, or currency conversion costs.</li>
                </ul>
            </div>

            {{-- 8 --}}
            <div class="terms-section" id="prohibited">
                <h2><span class="num">8</span> Prohibited Conduct</h2>
                <p>You may not use Bartaro to:</p>
                <ul>
                    <li>List or trade <strong>illegal items</strong> (weapons, drugs, counterfeit goods, stolen property, etc.).</li>
                    <li>Harass, threaten, or abuse other users.</li>
                    <li>Post false, misleading, or deceptive listings.</li>
                    <li>Create fake accounts or impersonate others.</li>
                    <li>Attempt to circumvent platform safety features or payments.</li>
                    <li>Scrape, bot, or automate interactions with the platform.</li>
                    <li>Conduct transactions off-platform to avoid fees after initiating contact here.</li>
                    <li>Upload malicious code, spam, or unsolicited commercial messages.</li>
                </ul>
                <div class="terms-warn">
                    Violations may result in immediate account termination and may be reported to relevant authorities.
                </div>
            </div>

            {{-- 9 --}}
            <div class="terms-section" id="intellectual">
                <h2><span class="num">9</span> Intellectual Property</h2>
                <p>The Bartaro name, logo, and platform design are our intellectual property. You may not reproduce, distribute, or create derivative works without express written permission.</p>
                <p>By posting content (photos, descriptions, reviews) you grant Bartaro a non-exclusive, royalty-free, worldwide licence to display and use that content on the platform for the purpose of operating the Service.</p>
                <p>You retain ownership of content you post. You may remove your content at any time by deleting your listings or account.</p>
            </div>

            {{-- 10 --}}
            <div class="terms-section" id="privacy">
                <h2><span class="num">10</span> Privacy</h2>
                <p>We collect personal data (name, email, phone, location) solely for the purpose of operating the platform. We do not sell personal data to third parties.</p>
                <ul>
                    <li>Passwords are hashed and never stored in plain text.</li>
                    <li>Payment processing is handled entirely by PayPal; Bartaro does not store card or bank details.</li>
                    <li>Google OAuth users' data is handled in accordance with Google's privacy policies.</li>
                    <li>Uploaded images are stored securely and accessible only via unique URLs.</li>
                </ul>
                <p>You may request deletion of your account and associated data at any time by contacting us.</p>
            </div>

            {{-- 11 --}}
            <div class="terms-section" id="disclaimers">
                <h2><span class="num">11</span> Disclaimers</h2>
                <p>The Service is provided <strong>"as is"</strong> and <strong>"as available"</strong> without warranties of any kind, express or implied, including but not limited to merchantability, fitness for a particular purpose, or non-infringement.</p>
                <p>We do not warrant that:</p>
                <ul>
                    <li>The platform will be uninterrupted, error-free, or free of viruses.</li>
                    <li>Any trade will be completed or that items will be as described by the listing party.</li>
                    <li>User-generated content is accurate or reliable.</li>
                </ul>
            </div>

            {{-- 12 --}}
            <div class="terms-section" id="limitation">
                <h2><span class="num">12</span> Limitation of Liability</h2>
                <p>To the maximum extent permitted by law, Bartaro and its affiliates shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, or goodwill, arising out of or in connection with your use of the Service.</p>
                <p>Our total aggregate liability to you for any claim arising from use of the Service shall not exceed the fees (if any) paid by you to Bartaro in the <strong>three months preceding the claim</strong>.</p>
            </div>

            {{-- 13 --}}
            <div class="terms-section" id="termination">
                <h2><span class="num">13</span> Termination</h2>
                <p>You may close your account at any time from your profile settings. We may suspend or terminate your account if you violate these terms, with or without notice.</p>
                <p>Upon termination: your listings will be removed, any pending trades may be cancelled, and escrow funds (if any) will be handled at admin discretion in accordance with Section 6.</p>
                <p>Sections 9 (Intellectual Property), 11 (Disclaimers), and 12 (Limitation of Liability) survive termination.</p>
            </div>

            {{-- 14 --}}
            <div class="terms-section" id="contact">
                <h2><span class="num">14</span> Contact</h2>
                <p>Questions about these terms? Reach us at:</p>
                <div class="d-flex flex-wrap gap-3 mt-3">
                    <div style="background:var(--p-light);border-radius:.65rem;padding:.85rem 1.25rem;display:flex;align-items:center;gap:.65rem;">
                        <i class="bi bi-envelope-fill" style="color:var(--p);font-size:1.1rem;"></i>
                        <div>
                            <div style="font-size:.7rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Email</div>
                            <div style="font-weight:600;color:var(--text);font-size:.9rem;">legal@bartaro.com</div>
                        </div>
                    </div>
                    <div style="background:var(--p-light);border-radius:.65rem;padding:.85rem 1.25rem;display:flex;align-items:center;gap:.65rem;">
                        <i class="bi bi-geo-alt-fill" style="color:var(--p);font-size:1.1rem;"></i>
                        <div>
                            <div style="font-size:.7rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Address</div>
                            <div style="font-weight:600;color:var(--text);font-size:.9rem;">Tbilisi, Georgia</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom accept block --}}
            <div style="background:linear-gradient(135deg,#eef2ff,#f5f3ff);border:1px solid #c7d2fe;border-radius:var(--radius-lg);padding:2rem;text-align:center;margin-top:1rem;">
                <i class="bi bi-check-circle-fill" style="font-size:2rem;color:var(--p);display:block;margin-bottom:.75rem;"></i>
                <h5 class="fw-700 mb-2" style="color:var(--text);">By using Bartaro, you agree to these terms.</h5>
                <p class="text-muted small mb-3">Last updated May 2, 2026. We'll notify you of any significant changes.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-arrow-right me-1"></i> Browse Listings
                </a>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
// Highlight active section in sidebar as user scrolls
(function () {
    const links    = document.querySelectorAll('.terms-nav a');
    const sections = document.querySelectorAll('.terms-section');
    if (!links.length) return;

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                links.forEach(l => l.classList.remove('active'));
                const active = document.querySelector(`.terms-nav a[href="#${entry.target.id}"]`);
                if (active) active.classList.add('active');
            }
        });
    }, { rootMargin: '-30% 0px -60% 0px' });

    sections.forEach(s => observer.observe(s));
})();
</script>
@endpush

@endsection
