<div class="mb-3">
    <label class="input-label" style="display:block;text-align:left;color:#6b7280;font-size:13px;margin-bottom:8px;font-weight:600;">@lang('Verification Code')</label>
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
            background-color: #f8fafc !important;
            border: 1.5px solid #d5e4f7 !important;
            color: #123b66 !important;
            border-radius: 10px !important;
            width: clamp(35px, 12vw, 45px) !important;
            height: clamp(40px, 14vw, 50px) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 18px !important;
            font-weight: 700 !important;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .verification-code #verification-code:focus ~ .boxes span {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
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
            let val = $(this).val().replace(/\D/g, '').slice(0, 6);
            $(this).val(val);
            let spans = $('.boxes span');
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
