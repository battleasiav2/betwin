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

    html, body {
        max-width: 100vw;
        overflow-x: hidden;
        touch-action: manipulation;
    }

    body { 
        background-color: var(--bg-color); 
        color: var(--text-main); 
        font-family: 'Roboto', sans-serif; 
        padding-bottom: 90px; 
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }
    input, textarea { user-select: auto; }

    .profile-setting-wrapper {
        min-height: 100vh;
    }

    /* ─── HEADER ─── */
    .header-bg { 
        background: linear-gradient(135deg, #0f1419 0%, #151b24 55%, #1a2330 100%);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 50;
    }
    .header-bg a {
        color: #ffffff;
        font-size: 20px;
        padding: 4px;
        text-decoration: none;
    }
    .header-bg h1 {
        color: #ffffff;
        font-weight: 700;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .header-bg button {
        color: #ffffff;
        font-size: 20px;
        padding: 4px;
        background: none;
        border: none;
        cursor: pointer;
    }

    /* ─── PROFILE CARD ─── */
    .profile-card {
        background: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
        text-align: center;
        border: 1px solid var(--border-color);
        position: relative;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin: 20px 16px 16px;
    }
    .profile-bg {
        height: 90px;
        background: linear-gradient(135deg, #e8b84a 0%, #0f1419 100%);
        border-bottom: 3px solid var(--accent-color);
    }
    
    .avatar-container {
        width: 76px;
        height: 76px;
        margin: -38px auto 10px auto;
        position: relative;
        z-index: 10;
        cursor: pointer;
    }
    .avatar-img-box {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #151b24;
        border: 3px solid #151b24;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .avatar-img-box img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-edit-btn {
        position: absolute;
        bottom: 0;
        right: -4px;
        background: var(--header-color);
        color: white;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        font-size: 11px;
        cursor: pointer;
    }

    /* ─── INFO CONTAINER ─── */
    .info-container {
        background-color: var(--blue-light);
        border: 1px solid rgba(45,212,168,0.3);
        border-left: 4px solid var(--header-color);
        border-radius: 8px;
        padding: 16px;
        margin: 0 16px 16px;
    }

    /* ─── LIST CARD ─── */
    .list-card {
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        padding: 0 16px;
        margin: 0 16px;
    }

    .list-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 16px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .list-row:last-child { border-bottom: none; }
    
    .row-icon { 
        color: var(--header-color);
        font-size: 16px;
        width: 36px;
        height: 36px; 
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #0f1419;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        margin-top: 2px;
    }
    
    .row-content { flex: 1; min-width: 0; }
    .row-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 4px;
    }
    .row-title { 
        color: var(--header-color); 
        font-size: 12.5px; 
        font-weight: 800; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
    }
    .row-sub { 
        font-size: 13px; 
        color: var(--text-muted); 
        font-weight: 600; 
        display: block; 
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis; 
    }
    .row-sub.highlight { color: #e8b84a; font-weight: 700; }
    .row-sub.not-set { color: #9ca3af; font-style: italic; }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 5px 10px;
        border-radius: 4px;
        transition: 0.2s;
        white-space: nowrap;
        letter-spacing: 0.5px;
        flex-shrink: 0;
        cursor: pointer;
        border: none;
        text-decoration: none;
    }
    .btn-add { 
        background: var(--blue-light); 
        color: var(--header-color); 
        border: 1px solid rgba(45,212,168,0.3); 
    }
    .btn-add:active { background: #bae6fd; transform: scale(0.95); }
    .btn-verified { 
        background: #dcfce7; 
        color: #10b981; 
        border: 1px solid #bbf7d0; 
    }

    .pill { 
        display: inline-flex; 
        align-items: center; 
        gap: 4px;
        font-size: 9px; 
        font-weight: 800; 
        padding: 3px 8px; 
        border-radius: 4px; 
        margin-top: 6px; 
        margin-right: 4px; 
        text-transform: uppercase; 
        letter-spacing: 0.5px;
    }
    .pill-red { background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; }
    .pill-yellow { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
    .pill-green { background: #dcfce7; color: #10b981; border: 1px solid #bbf7d0; }

    .privacy-notice {
        margin: 24px 16px;
        display: flex;
        gap: 12px;
        font-size: 11px;
        color: #6b7280;
        align-items: flex-start;
        background: #eff6ff;
        padding: 16px;
        border-radius: 12px;
        border: 1px solid #bfdbfe;
    }
    .privacy-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(45,212,168,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid rgba(45,212,168,0.3);
    }

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

<div class="profile-setting-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.home') }}"><i class="fas fa-home"></i></a>
        <h1>Personal Info</h1>
        <a href="{{ route('user.account') }}"><i class="fas fa-times"></i></a>
    </div>

    <div style="padding-top: 20px;">
        
        <!-- PROFILE CARD -->
        <div class="profile-card">
            <div class="profile-bg"></div>
            
            <form id="imgForm" method="POST" enctype="multipart/form-data" action="{{ route('user.profile.setting') }}" style="display:none;">
                @csrf
                <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="document.getElementById('imgForm').submit()">
            </form>

            <div class="avatar-container" onclick="document.getElementById('avatarInput').click()">
                <div class="avatar-img-box">
                    @if(auth()->user()->image)
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . auth()->user()->image, getFileSize('userProfile')) }}" alt="Avatar">
                    @else
                        <i class="fas fa-camera" style="color:#d1d5db; font-size:28px;"></i>
                    @endif
                </div>
                <div class="avatar-edit-btn"><i class="fas fa-pen"></i></div>
            </div>

            <div style="padding-bottom: 16px;">
                <h2 style="color:#e8b84a; font-weight:900; font-size:22px; display:flex; justify-content:center; align-items:center; gap:8px;">
                    {{ auth()->user()->username }}
                </h2>
                <div style="display:inline-block; background:#f0f9ff; color:#0284c7; font-size:10px; font-weight:700; padding:4px 10px; border-radius:20px; border:1px solid #bae6fd; margin-top:6px; text-transform:uppercase; letter-spacing:0.5px;">
                    {{ auth()->user()->vip_level ? 'VIP ' . auth()->user()->vip_level : 'Standard Member' }}
                </div>
                <p style="font-size:11px; color:#9ca3af; margin-top:8px; font-weight:500;">
                    Joined: {{ showDateTime(auth()->user()->created_at, 'd M, Y') }}
                </p>
            </div>

            <div style="background:#f8fafc; border-top:1px solid #f3f4f6; padding:16px; display:flex; justify-content:space-between; align-items:center; font-size:12px;">
                <span style="color:#6b7280; font-weight:700;">
                    VIP Points (VP) 
                    <span style="color:#43a047; margin-left:4px; font-weight:900; font-size:15px;">0</span>
                </span>
                <a href="#" style="color:#e8b84a; font-weight:700; display:flex; align-items:center; gap:4px; text-decoration:none; background:rgba(232,184,74,0.12); padding:6px 10px; border-radius:6px; border:1px solid rgba(232,184,74,0.3);">
                    My VIP <i class="fas fa-angle-double-right" style="font-size:10px;"></i>
                </a>
            </div>
        </div>

        <!-- VERIFICATION INFO -->
        <div class="info-container">
            <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:12px;">
                <i class="fas fa-info-circle" style="color:#e8b84a; font-size:18px; flex-shrink:0;"></i>
                <p style="color:#e8b84a; font-size:11.5px; font-weight:600; line-height:1.5;">
                    Please complete the verification below before you proceed with withdrawal requests.
                </p>
            </div>

            <div style="border-bottom:1px solid #bae6fd; margin-bottom:12px; opacity:0.6;"></div>

            <div>
                <div style="color:#e8b84a; font-size:11px; font-weight:900; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">Personal Info</div>
                <div style="display:flex; flex-wrap:wrap;">
                    @if(!auth()->user()->firstname || !auth()->user()->lastname)
                        <span class="pill pill-red"><i class="fas fa-circle" style="font-size:5px;"></i> Full Name</span>
                    @else
                        <span class="pill pill-green"><i class="fas fa-check-circle" style="font-size:8px;"></i> Full Name</span>
                    @endif
                </div>
            </div>

            <div style="margin-top:12px;">
                <div style="color:#e8b84a; font-size:11px; font-weight:900; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">Contact Info</div>
                <div style="display:flex; flex-wrap:wrap;">
                    @if(!auth()->user()->mobile)
                        <span class="pill pill-yellow"><i class="fas fa-circle" style="font-size:5px;"></i> Phone Number</span>
                    @else
                        <span class="pill pill-green"><i class="fas fa-check-circle" style="font-size:8px;"></i> Phone Number</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- LIST CARD -->
        <div class="list-card">
            <div class="list-row">
                <div class="row-icon"><i class="far fa-id-card"></i></div>
                <div class="row-content">
                    <div class="row-header">
                        <span class="row-title">Full Name</span>
                    </div>
                    <span class="row-sub {{ auth()->user()->fullname ? 'highlight' : 'not-set' }}">
                        {{ auth()->user()->fullname ?? 'Not Set' }}
                    </span>
                </div>
            </div>

            <div class="list-row">
                <div class="row-icon"><i class="fas fa-mobile-alt"></i></div>
                <div class="row-content">
                    <div class="row-header">
                        <span class="row-title">Phone Number</span>
                        @if(auth()->user()->mobile)
                            <span class="btn-action btn-verified"><i class="fas fa-check-circle" style="margin-right:4px;"></i> Verified</span>
                        @endif
                    </div>
                    <span class="row-sub highlight">
                        {{ auth()->user()->mobile ? '0' . auth()->user()->mobile : 'Not Set' }}
                    </span>
                </div>
            </div>

            <div class="list-row">
                <div class="row-icon"><i class="far fa-envelope"></i></div>
                <div class="row-content">
                    <div class="row-header">
                        <span class="row-title">Email Address</span>
                    </div>
                    <span class="row-sub {{ auth()->user()->email ? 'highlight' : 'not-set' }}">
                        {{ auth()->user()->email ?? 'Not Set' }}
                    </span>
                </div>
            </div>

            <div class="list-row">
                <div class="row-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="row-content">
                    <div class="row-header">
                        <span class="row-title">Country</span>
                    </div>
                    <span class="row-sub highlight">Bangladesh</span>
                </div>
            </div>
        </div>

        <!-- PRIVACY NOTICE -->
        <div class="privacy-notice">
            <div class="privacy-icon">
                <i class="fas fa-lock" style="color:#e8b84a; font-size:13px;"></i>
            </div>
            <p style="line-height:1.6; font-weight:500;">
                For privacy and security, Information cannot be modified after confirmation. Please 
                <a href="{{ route('ticket.open') }}" style="color:#e8b84a; font-weight:700; text-decoration:underline;">contact customer service</a> 
                for help.
            </p>
        </div>

    </div>

</div>

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
    // Avatar upload trigger
    document.querySelector('.avatar-container')?.addEventListener('click', function() {
        document.getElementById('avatarInput').click();
    });
</script>

@endsection
