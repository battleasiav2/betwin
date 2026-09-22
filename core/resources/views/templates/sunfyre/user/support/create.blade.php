@extends($activeTemplate . 'layouts.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    :root {
        --bg-color: #e8f0fa;
        --header-color: #154b77;
        --accent-color: #43a047;
        --card-bg: #ffffff;
        --text-main: #333333;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --blue-light: #e0f2fe;
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

    .ticket-create-wrapper {
        min-height: 100vh;
    }

    /* â”€â”€â”€ HEADER â”€â”€â”€ */
    .header-bg { 
        background: linear-gradient(135deg, #0f395c 0%, #1a5c92 100%);
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

    /* â”€â”€â”€ CARD â”€â”€â”€ */
    .white-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 24px;
        margin: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    /* â”€â”€â”€ FORM ELEMENTS â”€â”€â”€ */
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--header-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid var(--border-color);
        color: #333;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 500;
        outline: none;
        transition: 0.2s;
        font-family: 'Roboto', sans-serif;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: var(--header-color);
        box-shadow: 0 0 0 3px rgba(21, 75, 119, 0.1);
    }
    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }
    .form-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23154b77' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 12px;
        cursor: pointer;
    }

    /* â”€â”€â”€ FILE UPLOAD â”€â”€â”€ */
    .file-upload-section {
        margin-bottom: 20px;
    }
    .file-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .add-file-btn {
        background: var(--blue-light);
        color: var(--header-color);
        font-size: 11px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 6px;
        border: 1px solid #bae6fd;
        cursor: pointer;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .add-file-btn:active { transform: scale(0.95); background: #bae6fd; }
    .add-file-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .file-hint {
        font-size: 10px;
        color: var(--text-muted);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .file-input-group {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        gap: 0;
    }
    .file-input-group input[type="file"] {
        flex: 1;
        background: #f8fafc;
        border: 1.5px solid var(--border-color);
        border-right: none;
        color: #333;
        border-radius: 8px 0 0 8px;
        padding: 10px 12px;
        font-size: 13px;
        outline: none;
    }
    .file-input-group input[type="file"]::file-selector-button {
        background: var(--blue-light);
        color: var(--header-color);
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 11px;
        margin-right: 10px;
    }
    .remove-file-btn {
        background: #fee2e2;
        color: #ef4444;
        border: 1px solid #fecaca;
        border-radius: 0 8px 8px 0;
        padding: 12px 14px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .remove-file-btn:active { background: #fecaca; }

    /* â”€â”€â”€ SUBMIT BUTTON â”€â”€â”€ */
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .submit-btn:active {
        transform: translateY(2px);
        box-shadow: 0 2px 4px rgba(67, 160, 71, 0.2);
    }

    /* â”€â”€â”€ BOTTOM NAV â”€â”€â”€ */
    .bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        z-index: 10000;
        padding: 0 10px 8px 10px;
        background: #e8f0fa;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: linear-gradient(180deg, #0e3d2c 0%, #0a2d1f 100%);
        border-radius: 999px;
        border: 1.5px solid #1a5c40;
        box-shadow:
            0 0 0 2px #e8f0fa,
            inset 0 1px 0 rgba(37,99,235,0.12),
            0 -2px 0 0 #2563eb,
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
        color: #3db88a;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 6px 0;
        transition: color 0.2s;
        position: relative;
    }

    .nav-item.active { color: #f5c518; }
    .nav-item.active span { border-bottom: 2px solid #f5c518; padding-bottom: 1px; }

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
        background: linear-gradient(145deg, #2563eb, #123b66);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 0 3px #e8f0fa,
            0 0 0 5px #2563eb,
            0 6px 20px rgba(37,99,235,0.45);
        font-size: 22px;
        color: #fff;
        margin-top: -18px;
        border: none;
    }

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="ticket-create-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('ticket.index') }}"><i class="fas fa-chevron-left"></i></a>
        <h1>Open Support Ticket</h1>
    </div>

    <!-- FORM -->
    <div class="white-card" style="margin-top: 16px;">
        <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data" id="ticketForm">
            @csrf
            
            <!-- Subject -->
            <div class="form-group">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="form-input" placeholder="Enter subject" required autocomplete="off">
            </div>

            <!-- Priority -->
            <div class="form-group">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select" required>
                    <option value="3">High</option>
                    <option value="2">Medium</option>
                    <option value="1" selected>Low</option>
                </select>
            </div>

            <!-- Message -->
            <div class="form-group">
                <label class="form-label">Message</label>
                <textarea name="message" rows="5" class="form-textarea" placeholder="Describe your issue here..." required>{{ old('message') }}</textarea>
            </div>

            <!-- Attachments -->
            <div class="file-upload-section">
                <div class="file-header">
                    <label class="form-label" style="margin-bottom: 0;">Attachments</label>
                    <button type="button" class="add-file-btn" id="addFileBtn">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
                <div class="file-hint">
                    <i class="fas fa-info-circle"></i> Max 5 files | Allowed: .jpg, .jpeg, .png, .pdf, .doc, .docx
                </div>
                <div id="fileUploadsContainer"></div>
            </div>

            <!-- Submit -->
            <button type="submit" class="submit-btn">
                <i class="fas fa-paper-plane"></i> Submit Ticket
            </button>
        </form>
    </div>

</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script>
    var fileAdded = 0;
    var maxFiles = 5;

    document.getElementById('addFileBtn').addEventListener('click', function() {
        fileAdded++;
        if (fileAdded >= maxFiles) {
            this.disabled = true;
            this.style.opacity = '0.5';
            this.style.cursor = 'not-allowed';
        }

        var container = document.getElementById('fileUploadsContainer');
        var div = document.createElement('div');
        div.className = 'file-input-group removeFileInput';
        div.innerHTML = `
            <input type="file" name="attachments[]" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
            <button type="button" class="remove-file-btn" onclick="removeFile(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    });

    function removeFile(btn) {
        var addBtn = document.getElementById('addFileBtn');
        fileAdded--;
        if (fileAdded < maxFiles) {
            addBtn.disabled = false;
            addBtn.style.opacity = '1';
            addBtn.style.cursor = 'pointer';
        }
        btn.closest('.fileUploadsContainer') ? 
            btn.parentElement.remove() : 
            btn.closest('.removeFileInput').remove();
    }
</script>

@endsection
