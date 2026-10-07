<style>
    .gate-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 28px 16px 48px;
        box-sizing: border-box;
        background:
            radial-gradient(ellipse at 82% 0%, rgba(45,212,168,0.18), transparent 42%),
            radial-gradient(ellipse at 8% 100%, rgba(232,184,74,0.14), transparent 46%),
            #0b0f14;
        color: #e8eef5;
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .gate-card {
        width: 100%;
        max-width: 440px;
        background: rgba(15, 20, 25, 0.9);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 22px;
        padding: 26px 22px 28px;
        box-shadow: 0 24px 60px rgba(0,0,0,0.45);
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
    }
    .gate-card::before {
        content: '';
        position: absolute;
        left: 0; right: 0; top: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, #2dd4a8, #e8b84a, transparent);
    }
    .gate-back {
        color: #e8b84a;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .gate-logo { text-align: center; margin: 8px 0 4px; }
    .gate-logo img { max-width: 210px; max-height: 64px; object-fit: contain; }
    .gate-kicker {
        display: inline-flex;
        margin: 8px auto 0;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(45,212,168,0.12);
        border: 1px solid rgba(45,212,168,0.28);
        color: #2dd4a8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .gate-card .gate-kicker { display: flex; width: fit-content; }
    .gate-title {
        margin: 12px 0 6px;
        text-align: center;
        font-size: 24px;
        font-weight: 800;
        color: #fff;
    }
    .gate-text {
        text-align: center;
        color: #8b97a8;
        font-size: 14px;
        line-height: 1.55;
        margin: 0 0 18px;
    }
    .gate-text a, .gate-note a { color: #2dd4a8; font-weight: 700; text-decoration: none; }
    .gate-text strong, .gate-text .text-white { color: #e8eef5; font-weight: 700; }
    .gate-field { text-align: left; margin-bottom: 14px; }
    .gate-field label, .gate-card .input-label, .gate-card .viser-form-data label {
        display: block;
        color: #8b97a8 !important;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .gate-input {
        position: relative;
    }
    .gate-input i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #2dd4a8;
        font-size: 14px;
        z-index: 2;
    }
    .gate-card .form-control,
    .gate-card .viser-form-data input,
    .gate-card .viser-form-data select,
    .gate-card .viser-form-data textarea {
        width: 100%;
        background: rgba(11,15,20,0.9) !important;
        border: 1px solid rgba(255,255,255,0.10) !important;
        border-radius: 12px !important;
        color: #e8eef5 !important;
        padding: 14px 14px 14px 42px !important;
        font-size: 15px !important;
        outline: none !important;
        box-shadow: none !important;
        height: auto !important;
    }
    .gate-card .viser-form-data input,
    .gate-card .viser-form-data select,
    .gate-card .viser-form-data textarea { padding-left: 14px !important; }
    .gate-card .form-control:focus,
    .gate-card .viser-form-data input:focus,
    .gate-card .viser-form-data select:focus,
    .gate-card .viser-form-data textarea:focus {
        border-color: rgba(45,212,168,0.55) !important;
        box-shadow: 0 0 0 3px rgba(45,212,168,0.12) !important;
    }
    .gate-card .form-control::placeholder { color: #8b97a8; }
    .gate-card select option, .gate-card .viser-form-data select option { background: #151b24; color: #fff; }
    .gate-card input[type="file"] { padding: 10px !important; }
    .gate-card input[type="file"]::file-selector-button {
        background: #e8b84a;
        color: #0b0f14;
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-weight: 800;
        margin-right: 10px;
    }
    .gate-btn, .gate-card .submit-btn {
        width: 100%;
        display: block;
        text-align: center;
        margin-top: 8px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(45,212,168,0.22) 0%, #151b24 58%);
        border: 1px solid rgba(45,212,168,0.4);
        border-radius: 12px;
        padding: 14px;
        font-size: 16px;
        font-weight: 800;
        color: #e8eef5 !important;
        cursor: pointer;
        text-decoration: none;
        box-sizing: border-box;
    }
    .gate-btn::before, .gate-card .submit-btn::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0; width: 3px;
        background: #2dd4a8;
    }
    .gate-note { text-align: center; color: #8b97a8; font-size: 13px; margin-top: 16px; line-height: 1.5; }
    .gate-alert {
        background: rgba(239,68,68,0.12);
        border: 1px solid rgba(239,68,68,0.35);
        color: #fca5a5;
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        margin-bottom: 14px;
        font-size: 14px;
    }
    .gate-card .verification-code span {
        background: rgba(11,15,20,0.9) !important;
        border: 1px solid rgba(255,255,255,0.12) !important;
        color: #e8b84a !important;
        border-radius: 12px !important;
        width: 46px !important;
        height: 54px !important;
        font-size: 20px !important;
    }
    .gate-card .verification-code #verification-code:focus ~ .boxes span {
        border-color: rgba(45,212,168,0.7) !important;
        box-shadow: 0 0 0 3px rgba(45,212,168,0.12) !important;
    }
    .gate-card input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px #0b0f14 inset !important;
        -webkit-text-fill-color: #e8eef5 !important;
    }
    @media (max-width: 520px) {
        .gate-card { padding: 22px 16px 24px; border-radius: 18px; }
        .gate-title { font-size: 22px; }
    }
</style>
