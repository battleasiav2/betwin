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

    .pin-page-wrapper {
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

    /* ─── CARD ─── */
    .white-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 24px;
        margin: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--header-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: block;
    }

    /* ─── PIN INPUT BOXES ─── */
    .pin-input-wrapper {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .pin-box {
        width: 55px;
        height: 60px;
        text-align: center;
        font-size: 24px;
        font-weight: 900;
        background: #0f1419;
        color: var(--header-color);
        border: 2px solid var(--border-color);
        border-radius: 10px;
        outline: none;
        transition: all 0.2s;
        caret-color: transparent;
    }
    .pin-box:focus {
        border-color: var(--header-color);
        box-shadow: 0 0 0 3px rgba(21, 75, 119, 0.1);
        background: #151b24;
    }
    .pin-box.filled {
        border-color: var(--accent-color);
        background: #f0fdf4;
    }

    /* ─── SUBMIT BUTTON ─── */
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
        margin-top: 8px;
    }
    .submit-btn:active {
        transform: translateY(2px);
        box-shadow: 0 2px 4px rgba(67, 160, 71, 0.2);
    }

    /* ─── INFO BOX ─── */
    .info-box {
        background: var(--blue-light);
        border: 1px solid rgba(45,212,168,0.3);
        border-left: 4px solid var(--header-color);
        border-radius: 8px;
        padding: 12px 16px;
        margin: 0 16px 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .info-box i {
        color: var(--header-color);
        font-size: 16px;
        margin-top: 2px;
        flex-shrink: 0;
    }
    .info-box p {
        font-size: 11px;
        color: var(--header-color);
        font-weight: 600;
        line-height: 1.5;
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
        .pin-box { width: 65px; height: 70px; font-size: 28px; }
    }
</style>

<div class="pin-page-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.account') }}"><i class="fas fa-chevron-left"></i></a>
        <h1>Change Transaction PIN</h1>
    </div>

    <!-- INFO BOX -->
    <div class="info-box" style="margin-top: 16px;">
        <i class="fas fa-shield-alt"></i>
        <p>Your transaction PIN is required for withdrawals. Please keep it secure and do not share it with anyone.</p>
    </div>

    <!-- PIN FORM -->
    <div class="white-card">
        <form action="{{ route('user.withdraw.pin.update') }}" method="post" id="pinForm" onsubmit="return validatePins()">
            @csrf
            
            <!-- Old PIN -->
            <div class="form-group">
                <label class="form-label">Old PIN</label>
                <div class="pin-input-wrapper" id="old_pin_wrapper">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                </div>
                <input type="hidden" name="old_pin" id="old_pin_hidden">
            </div>

            <!-- New PIN -->
            <div class="form-group">
                <label class="form-label">New PIN</label>
                <div class="pin-input-wrapper" id="new_pin_wrapper">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                </div>
                <input type="hidden" name="password" id="new_pin_hidden">
            </div>

            <!-- Confirm New PIN -->
            <div class="form-group">
                <label class="form-label">Confirm New PIN</label>
                <div class="pin-input-wrapper" id="confirm_pin_wrapper">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                    <input type="tel" class="pin-box" maxlength="1" inputmode="numeric" autocomplete="off" pattern="[0-9]">
                </div>
                <input type="hidden" name="password_confirmation" id="confirm_pin_hidden">
            </div>

            <button type="submit" class="submit-btn">Update PIN</button>
        </form>
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
    function setupPinInputs(wrapperId, hiddenInputId) {
        const wrapper = document.getElementById(wrapperId);
        const inputs = wrapper.querySelectorAll('.pin-box');
        const hiddenInput = document.getElementById(hiddenInputId);

        function updateHiddenValue() {
            let pin = '';
            inputs.forEach(function(input) {
                pin += input.value;
                if (input.value.length === 1) {
                    input.classList.add('filled');
                } else {
                    input.classList.remove('filled');
                }
            });
            hiddenInput.value = pin;
        }

        inputs.forEach(function(input, index) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateHiddenValue();
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('focus', function() {
                this.select();
            });
        });
    }

    // Initialize all three PIN input groups
    setupPinInputs('old_pin_wrapper', 'old_pin_hidden');
    setupPinInputs('new_pin_wrapper', 'new_pin_hidden');
    setupPinInputs('confirm_pin_wrapper', 'confirm_pin_hidden');

    function validatePins() {
        const newPin = document.getElementById('new_pin_hidden').value;
        const confirmPin = document.getElementById('confirm_pin_hidden').value;
        
        if (newPin !== confirmPin) {
            alert('New PIN and Confirm PIN do not match!');
            return false;
        }
        
        if (newPin.length !== 4 || confirmPin.length !== 4) {
            alert('Please enter a complete 4-digit PIN!');
            return false;
        }
        
        return true;
    }
</script>

@endsection
