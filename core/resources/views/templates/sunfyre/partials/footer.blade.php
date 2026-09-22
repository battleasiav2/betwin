@php
    $footer = getContent('footer.content', true);
@endphp

<footer class="footer-area" style="position: relative; background: #123B66; margin-top: 0; padding-top: 0;">
    
    <div style="width: 100%; height: 2px; background: linear-gradient(90deg, transparent, #00d094, transparent); margin-bottom: 20px;"></div>

    <div class="footer-area__thumb" style="position: absolute; bottom: 0; left: 0; width: 100%; opacity: 0.2; pointer-events: none;">
        
    </div>

    <div style="padding-top: 10px; padding-bottom: 10px; position: relative; z-index: 2;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="footer-circle-logo" style="width: 70px; height: 70px; border-radius: 50%; overflow: hidden; border: 2px solid #F4B942; display: flex; align-items: center; justify-content: center; background: #123B66;">
                        <img src="{{ asset('assets/images/logo_icon/favicon.png') }}" alt="{{ __(gs('site_name')) }}" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                </div>

                <div class="col ps-3">
                    <div class="footer-description">
                        <p style="font-size: 10px; line-height: 1.3; margin-bottom: 8px; color: #b0c4c2; text-align: left;">
                            BET369WIN website is operated by company, under license number GLH-OCCHKTW07080120 issued to it and regulated by Gaming Services Provider N.V., authorized by the Government of Curaçao under license number 365JAZ. Support: support@bet369win.com
                        </p>
                    </div>

                    <div class="footer-icons d-flex align-items-center">
                        <a href="https://www.facebook.com/bet369win" target="_blank" rel="noopener" style="background: #1877f2; width: 26px; height: 26px; display: flex; justify-content: center; align-items: center; border-radius: 50%; margin-right: 8px; color: white; text-decoration: none; font-size: 12px;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://t.me/bet369win" target="_blank" rel="noopener" style="background: #0088cc; width: 26px; height: 26px; display: flex; justify-content: center; align-items: center; border-radius: 50%; margin-right: 8px; color: white; text-decoration: none; font-size: 12px;">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                        <span style="background: white; color: #d9534f; width: 26px; height: 26px; display: flex; justify-content: center; align-items: center; border-radius: 50%; font-weight: bold; border: 1px solid #d9534f; font-size: 9px;">
                            18+
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom" style="padding-top: 5px; padding-bottom: 10px; position: relative; z-index: 2;">
        <div class="container text-center">
            <img src="{{ asset('assets/images/frontend/footer/game.png') }}" alt="Partners" style="max-width: 85%; height: auto;">
        </div>
    </div>
</footer>