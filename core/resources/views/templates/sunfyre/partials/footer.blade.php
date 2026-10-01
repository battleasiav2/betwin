@php
    $footer = getContent('footer.content', true);
    $socialLinks = gs('social_links');
    $wa = trim(@$socialLinks->whatsapp ?? '');
    $tg = trim(@$socialLinks->telegram ?? '');
    $fb = trim(@$socialLinks->facebook ?? '');
@endphp

<footer class="footer-area custom-site-footer" style="position: relative; background: #0b0f14; margin-top: 0 !important; padding-top: 8px; padding-bottom: 8px;">

    <div style="padding: 10px 12px 0; position: relative; z-index: 2;">
        @include($activeTemplate . 'partials.live_withdraw_board')
    </div>
    
    <div style="width: 100%; height: 2px; background: linear-gradient(90deg, transparent, #2dd4a8, transparent); margin: 12px 0 20px;"></div>

    <div class="footer-brand" style="padding: 10px 16px; position: relative; z-index: 2;">
        <div class="footer-brand__row" style="display: flex; align-items: center; gap: 14px;">
                <div style="flex: 0 0 70px;">
                    <div class="footer-circle-logo" style="width: 70px; height: 70px; border-radius: 50%; overflow: hidden; border: 2px solid #e8b84a; display: flex; align-items: center; justify-content: center; background: #000;">
                        <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ gs('site_name') }}" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                </div>

                <div style="min-width: 0; flex: 1;">
                    <div class="footer-description">
                        <p style="font-size: 12px; line-height: 1.55; margin-bottom: 12px; color: #c5ced8; text-align: left;">
                            {{ gs('site_name') }} website is operated by company, under license number GLH-OCCHKTW07080120 issued to it and regulated by Gaming Services Provider N.V., authorized by the Government of Curaçao under license number 365JAZ.
                        </p>
                    </div>

                    <div class="footer-icons" style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                        @if($fb)
                        <a href="{{ $fb }}" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Facebook" style="width: 36px; height: 36px; display: flex; justify-content: center; align-items: center; border-radius: 50%; margin-right: 10px; overflow: hidden; text-decoration: none;">
                            <img src="{{ asset('assets/images/social/facebook.svg') }}" alt="Facebook" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </a>
                        @endif
                        @if($tg)
                        <a href="{{ $tg }}" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Telegram" style="width: 36px; height: 36px; display: flex; justify-content: center; align-items: center; border-radius: 50%; margin-right: 10px; overflow: hidden; text-decoration: none;">
                            <img src="{{ asset('assets/images/social/telegram.svg') }}" alt="Telegram" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </a>
                        @endif
                        @if($wa)
                        <a href="{{ $wa }}" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="WhatsApp" style="width: 36px; height: 36px; display: flex; justify-content: center; align-items: center; border-radius: 50%; margin-right: 10px; overflow: hidden; text-decoration: none;">
                            <img src="{{ asset('assets/images/social/whatsapp.svg') }}" alt="WhatsApp" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </a>
                        @endif
                        <span style="background: white; color: #d9534f; width: 26px; height: 26px; display: flex; justify-content: center; align-items: center; border-radius: 50%; font-weight: bold; border: 1px solid #d9534f; font-size: 9px;">
                            18+
                        </span>
                    </div>
                </div>
        </div>
    </div>

    <div class="footer-pay" style="padding: 18px 16px 8px; position: relative; z-index: 2;">
        <div style="font-size: 13px; font-weight: 800; letter-spacing: 0.7px; color: #f4f7fb; margin-bottom: 12px;">PAYMENT</div>
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <span style="padding: 12px 16px; border-radius: 12px; background: #3d0a3d; color: #ff4fa3; font-weight: 800; font-size: 15px;">bKash</span>
            <span style="padding: 12px 16px; border-radius: 12px; background: #3a1212; color: #ff6b6b; font-weight: 800; font-size: 15px;">Nagad</span>
            <span style="padding: 12px 16px; border-radius: 12px; background: #1a2433; color: #e8b84a; font-weight: 800; font-size: 15px;">Crypto</span>
        </div>
    </div>

    <div class="footer-bottom" style="padding: 18px 16px 8px; position: relative; z-index: 2;">
        <div style="font-size: 13px; font-weight: 800; letter-spacing: 0.7px; color: #f4f7fb; margin-bottom: 14px;">PARTNERS</div>
        <img src="{{ asset('assets/images/frontend/footer/game.png') }}" alt="Partners" style="width: 100%; max-width: 720px; height: auto; display: block; margin: 0 auto;">
    </div>
</footer>
