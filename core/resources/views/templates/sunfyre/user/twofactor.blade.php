@extends($activeTemplate . 'layouts.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    :root {
        --bg-color: #0b0f14;
        --header-color: #e8b84a;
        --accent-color: #2dd4a8;
        --card-bg: #151b24;
        --text-main: #e8eef5;
        --text-muted: #8b97a8;
        --border-color: rgba(255, 255, 255, 0.08);
        --blue-light: rgba(45, 212, 168, 0.12);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    html, body { max-width: 100vw; overflow-x: hidden; touch-action: manipulation; }
    
    body { 
        background-color: var(--bg-color); 
        color: var(--text-main); 
        font-family: 'Roboto', sans-serif; 
        padding-bottom: 90px; 
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }

    .twofa-wrapper {
        min-height: 100vh;
    }

    /* ─── HEADER ─── */
    .header-bg { 
        background: linear-gradient(135deg, #0f1419 0%, #151b24 55%, #1a2330 100%);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        position: sticky;
        top: 0;
        z-index: 50;
    }
    .header-bg a {
        color: #ffffff;
        font-size: 20px;
        padding: 4px;
        text-decoration: none;
        transition: transform 0.15s;
    }
    .header-bg a:active { transform: scale(0.9); }
    .header-bg h1 {
        color: #ffffff;
        font-weight: 800;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* ─── SECTION TITLE ─── */
    .section-title {
        border-left: 4px solid var(--accent-color);
        padding-left: 10px;
        font-size: 14px;
        color: var(--header-color);
        margin-bottom: 16px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ─── CARD ─── */
    .white-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        margin: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    /* ─── QR CODE ─── */
    .qr-wrapper {
        text-align: center;
        margin-bottom: 20px;
    }
    .qr-img {
        max-width: 160px;
        border-radius: 8px;
        padding: 8px;
        background: #151b24;
        border: 2px solid #e5e7eb;
        display: inline-block;
    }
    .qr-img img {
        width: 100%;
        height: auto;
        display: block;
    }

    /* ─── COPY INPUT ─── */
    .copy-input-group {
        display: flex;
        align-items: center;
        background: #0f1419;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 16px;
        position: relative;
    }
    .copy-input-group input {
        flex: 1;
        background: transparent;
        border: none;
        color: var(--header-color);
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 700;
        outline: none;
    }
    .copy-input-group .copy-btn {
        background: linear-gradient(to bottom, #4caf50, #388e3c);
        color: #ffffff;
        border: none;
        padding: 12px 16px;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.15s;
        flex-shrink: 0;
    }
    .copy-input-group .copy-btn:active { transform: scale(0.95); }
    .copy-input-group .copy-btn.copied {
        background: #22c55e;
    }

    /* ─── FORM INPUT ─── */
    .form-group {
        margin-bottom: 16px;
    }
    .form-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--header-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }
    .form-input {
        width: 100%;
        background: #0f1419;
        border: 1.5px solid var(--border-color);
        color: #e8eef5;
        border-radius: 8px;
        padding: 14px 16px;
        font-size: 14px;
        font-weight: 600;
        outline: none;
        transition: 0.2s;
    }
    .form-input:focus {
        border-color: var(--header-color);
        box-shadow: 0 0 0 3px rgba(21, 75, 119, 0.1);
    }

    .submit-btn {
        width: 100%;
        background: linear-gradient(to bottom, #4caf50, #388e3c);
        color: #ffffff;
        font-weight: 800;
        font-size: 14px;
        padding: 14px;
        border-radius: 8px;
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(67, 160, 71, 0.2);
        transition: 0.2s;
    }
    .submit-btn:active {
        transform: translateY(2px);
        box-shadow: 0 2px 4px rgba(67, 160, 71, 0.2);
    }
    .submit-btn.danger {
        background: linear-gradient(to bottom, #ef4444, #dc2626);
        box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
    }

    /* ─── HELP SECTION ─── */
    .help-section {
        background: var(--blue-light);
        border: 1px solid rgba(45,212,168,0.3);
        border-radius: 8px;
        padding: 12px;
        margin-top: 16px;
        text-align: center;
    }
    .help-section p {
        font-size: 11px;
        color: var(--text-muted);
        line-height: 1.5;
    }
    .help-section a {
        color: var(--header-color);
        font-weight: 700;
        text-decoration: underline;
    }

    /* ─── INFO TEXT ─── */
    .info-text {
        font-size: 12px;
        color: var(--text-muted);
        text-align: center;
        margin-bottom: 16px;
        font-weight: 500;
    }

    /* ─── COPIED TOAST ─── */
    .copy-toast {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: #22c55e;
        color: #fff;
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        z-index: 99999;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }
    .copy-toast.show { opacity: 1; }

    /* ─── BOTTOM NAV ─── */
    .bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        z-index: 10000;
        padding: 0 10px 8px 10px;
        background: #0b0f14;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: linear-gradient(180deg, #1a2330 0%, #151b24 100%);
        border-radius: 999px;
        border: 1.5px solid #2a3544;
        box-shadow:
            0 0 0 2px #0b0f14,
            inset 0 1px 0 rgba(45,212,168,0.18),
            0 -2px 0 0 #2dd4a8,
            0 4px 24px rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 0 6px;
        position: relative;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        flex: 1;
        text-decoration: none !important;
        color: #8b97a8;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 6px 0;
        transition: color 0.2s;
        position: relative;
    }

    .nav-item.active { color: #e8b84a; }
    .nav-item.active span { border-bottom: 2px solid #e8b84a; padding-bottom: 1px; }

    .nav-item i { font-size: 20px; }
    .nav-item span { font-size: 10px; font-weight: 700; }

    .center-item {
        position: relative;
        flex: 1;
        justify-content: flex-end;
        padding-bottom: 0;
    }

    .center-icon-circle {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(145deg, #2dd4a8, #1fa88a);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 0 3px #0b0f14,
            0 0 0 5px #2dd4a8,
            0 6px 20px rgba(45,212,168,0.55);
        font-size: 22px;
        color: #fff;
        margin-top: -18px;
        border: none;
    }

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="twofa-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.home') }}"><i class="fas fa-chevron-left"></i></a>
        <h1>2FA Security</h1>
    </div>

    <div style="padding-top: 16px;">

        @if(!$user->ts)
            <!-- SETUP CARD -->
            <div class="white-card">
                <div class="section-title">Add Your Account</div>
                
                <p class="info-text">
                    Use the QR code or setup key on your Google Authenticator app.
                </p>

                <!-- QR Code -->
                <div class="qr-wrapper">
                    <div class="qr-img">
                        <img src="{{ $qrCodeUrl }}" alt="QR Code">
                    </div>
                </div>

                <!-- Setup Key -->
                <div class="form-group">
                    <label class="form-label">Setup Key</label>
                    <div class="copy-input-group">
                        <input type="text" id="keyInput" value="{{ $secret }}" readonly>
                        <button class="copy-btn" id="copyBoard" type="button">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="help-section">
                    <p>
                        Google Authenticator is a multifactor app for mobile devices. 
                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">Download App</a>
                    </p>
                </div>
            </div>
        @endif

        <!-- ENABLE/DISABLE CARD -->
        <div class="white-card">
            <div class="section-title">
                {{ $user->ts ? 'Disable 2FA Security' : 'Enable 2FA Security' }}
            </div>

            <form action="{{ $user->ts ? route('user.twofactor.disable') : route('user.twofactor.enable') }}" method="POST">
                @csrf
                <input name="key" type="hidden" value="{{ $secret }}">

                <div class="form-group">
                    <label class="form-label">Google Authenticator OTP</label>
                    <input class="form-input" name="code" type="text" required 
                           placeholder="Enter 6-digit code" autocomplete="off" maxlength="6"
                           style="text-align: center; letter-spacing: 8px; font-size: 20px; font-weight: 900;">
                </div>

                <button class="submit-btn {{ $user->ts ? 'danger' : '' }}" type="submit">
                    {{ $user->ts ? 'Disable 2FA' : 'Enable 2FA' }}
                </button>
            </form>
        </div>

    </div>

</div>

<!-- COPY TOAST -->
<div class="copy-toast" id="copyToast">Copied to clipboard!</div>

<!-- BOTTOM NAVIGATION -->
<div class="bottom-nav-container">
    <div class="bottom-nav">
        <a href="{{ route('user.home') }}" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('user.promotions') }}" class="nav-item">
            <i class="fas fa-gift"></i>
            <span>Promotion</span>
        </a>
        <a href="{{ route('user.referrals') }}" class="nav-item center-item">
            <div class="center-icon-circle"><i class="fas fa-share-nodes"></i></div>
            <span>Invite</span>
        </a>
        <a href="{{ route('user.redeem.index') }}" class="nav-item">
            <i class="fas fa-trophy"></i>
            <span>Reward</span>
        </a>
        <a href="{{ route('user.account') }}" class="nav-item">
            <i class="fas fa-user-circle"></i>
            <span>Member</span>
        </a>
    </div>
</div>

<script>
    document.getElementById('copyBoard').addEventListener('click', function() {
        var copyText = document.getElementById("keyInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(copyText.value).then(function() {
                showToast();
            });
        } else {
            document.execCommand("copy");
            showToast();
        }
    });

    function showToast() {
        const toast = document.getElementById('copyToast');
        toast.classList.add('show');
        setTimeout(function() {
            toast.classList.remove('show');
        }, 2000);
    }
</script>

@endsection
