<div class="mb-3">
    <label class="input-label">@lang('Verification Code')</label>
    <div class="verification-code">
        <input type="number" name="code" id="verification-code" class="form-control" required autocomplete="off" inputmode="numeric">
        <div class="boxes">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/verification-code.css') }}">
    <style>
        :root {
            --bg-dark: #0b0f14; 
            --input-bg: #151b24;
            --border-color: rgba(255,255,255,0.12);
            --primary-gold: #e8b84a; 
        }

        .verification-code {
            position: relative;
            display: flex;
            justify-content: center;
            width: 100%;
            touch-action: manipulation;
        }

        .verification-code #verification-code {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
            font-size: 16px !important;
        }

        .verification-code .boxes {
            display: flex;
            gap: clamp(4px, 2vw, 8px);
            justify-content: center;
            flex-wrap: nowrap;
            width: 100%;
        }

        .verification-code span {
            background-color: #0b0f14 !important;
            border: 1px solid rgba(255,255,255,0.12) !important;
            color: #e8b84a !important;
            border-radius: 12px !important;
            width: clamp(40px, 12vw, 46px) !important;
            height: clamp(48px, 14vw, 54px) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 18px !important;
            font-weight: 700 !important;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .verification-code #verification-code:focus ~ .boxes span {
            border-color: var(--primary-gold) !important;
            box-shadow: 0 0 5px rgba(232, 184, 74, 0.25);
        }

        .verification-code span::after, 
        .verification-code span::before,
        .verification-code::after, 
        .verification-code::before {
            display: none !important;
            content: none !important;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";
        $('#verification-code').on('input', function() {
            let val = $(this).val();
            let spans = $('.boxes span');
            
            if (val.length > 6) {
                $(this).val(val.substring(0, 6));
                val = $(this).val();
            }

            spans.html(''); 
            
            for (let i = 0; i < val.length; i++) {
                $(spans[i]).html(val[i]);
            }

            if (val.length == 6) {
                $('.submit-form').find('button[type=submit]').html('<i class="las la-spinner fa-spin"></i>');
                $('.submit-form').submit();
            }
        });
    </script>
@endpush