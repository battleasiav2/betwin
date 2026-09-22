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

    .ticket-view-wrapper {
        min-height: 100vh;
    }

    /* ─── HEADER ─── */
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
        flex: 1;
    }

    /* ─── CLOSE BUTTON ─── */
    .close-btn {
        background: #fee2e2;
        color: #ef4444;
        border: 1px solid #fecaca;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .close-btn:active { background: #fecaca; transform: scale(0.95); }

    /* ─── CARD ─── */
    .white-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        margin: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    /* ─── TICKET HEADER INFO ─── */
    .ticket-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .ticket-subject {
        font-size: 14px;
        font-weight: 700;
        color: var(--header-color);
    }
    .badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
    }
    .badge-open { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
    .badge-answered { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-replied { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .badge-closed { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }

    /* ─── REPLY FORM ─── */
    .form-textarea {
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
        resize: vertical;
        min-height: 100px;
        font-family: 'Roboto', sans-serif;
    }
    .form-textarea:focus {
        border-color: var(--header-color);
        box-shadow: 0 0 0 3px rgba(21, 75, 119, 0.1);
    }

    .add-file-btn {
        background: var(--blue-light);
        color: var(--header-color);
        font-size: 11px;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 6px;
        border: 1px solid #bae6fd;
        cursor: pointer;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 8px;
        width: 100%;
        justify-content: center;
    }
    .add-file-btn:active { transform: scale(0.95); background: #bae6fd; }
    .add-file-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    .file-hint {
        font-size: 10px;
        color: var(--text-muted);
        margin-bottom: 12px;
        text-align: center;
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
        font-size: 12px;
        outline: none;
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
    }
    .remove-file-btn:active { background: #fecaca; }

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
        margin-top: 12px;
    }
    .submit-btn:active { transform: translateY(2px); box-shadow: 0 2px 4px rgba(67, 160, 71, 0.2); }

    /* ─── PREVIOUS REPLIES ─── */
    .section-title {
        border-left: 4px solid var(--accent-color);
        padding-left: 10px;
        font-size: 14px;
        color: var(--header-color);
        margin: 24px 16px 16px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .reply-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        margin: 0 16px 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .reply-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
    }
    .reply-sender {
        font-size: 13px;
        font-weight: 700;
        color: var(--header-color);
    }
    .reply-sender small {
        font-size: 10px;
        color: var(--text-muted);
        font-weight: 500;
    }
    .reply-time {
        font-size: 10px;
        color: var(--text-muted);
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .reply-message {
        font-size: 14px;
        color: var(--text-main);
        line-height: 1.6;
        white-space: pre-wrap;
    }
    .attachment-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f8fafc;
        border: 1px solid var(--border-color);
        padding: 6px 12px;
        border-radius: 6px;
        color: var(--header-color);
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        margin-top: 8px;
        margin-right: 6px;
        transition: all 0.15s;
    }
    .attachment-link:active { background: #e0f2fe; }

    /* ─── EMPTY STATE ─── */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin: 0 16px;
    }
    .empty-state img {
        width: 60px;
        opacity: 0.3;
        margin-bottom: 12px;
    }
    .empty-state h5 {
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 600;
    }

    /* ─── CONFIRMATION MODAL ─── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .modal-overlay.hidden { display: none; }
    .modal-box {
        background: #ffffff;
        border-radius: 12px;
        width: 100%;
        max-width: 360px;
        padding: 24px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        text-align: center;
    }
    .modal-box h3 {
        font-size: 16px;
        font-weight: 800;
        color: var(--header-color);
        margin-bottom: 8px;
    }
    .modal-box p {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 20px;
    }
    .modal-buttons {
        display: flex;
        gap: 10px;
    }
    .modal-btn {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
        text-transform: uppercase;
    }
    .modal-btn:active { transform: scale(0.95); }
    .modal-btn-cancel {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }
    .modal-btn-confirm {
        background: #ef4444;
        color: #ffffff;
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
        background: #071f18;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: linear-gradient(180deg, #0e3d2c 0%, #0a2d1f 100%);
        border-radius: 999px;
        border: 1.5px solid #1a5c40;
        box-shadow:
            0 0 0 2px #071f18,
            inset 0 1px 0 rgba(30,200,130,0.18),
            0 -2px 0 0 #1edd96,
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
        background: linear-gradient(145deg, #1de9b6, #00897b);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 0 3px #071f18,
            0 0 0 5px #1edd96,
            0 6px 20px rgba(0,188,140,0.55);
        font-size: 22px;
        color: #fff;
        margin-top: -18px;
        border: none;
    }

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="ticket-view-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('ticket.index') }}"><i class="fas fa-chevron-left"></i></a>
        <h1>Ticket Conversation</h1>
        @if($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
            <button class="close-btn" onclick="openCloseModal()">
                <i class="fas fa-times-circle"></i> Close
            </button>
        @endif
    </div>

    <!-- TICKET INFO & REPLY FORM -->
    <div class="white-card" style="margin-top: 16px;">
        <div class="ticket-info" style="margin-bottom: 16px;">
            @php
                $statusClass = match($myTicket->status) {
                    0 => 'badge-open',
                    1 => 'badge-answered',
                    2 => 'badge-replied',
                    3 => 'badge-closed',
                    default => 'badge-open'
                };
                $statusText = match($myTicket->status) {
                    0 => 'Open',
                    1 => 'Answered',
                    2 => 'Replied',
                    3 => 'Closed',
                    default => 'Open'
                };
            @endphp
            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
            <span class="ticket-subject">[Ticket#{{ $myTicket->ticket }}] {{ $myTicket->subject }}</span>
        </div>

        @if($myTicket->status != Status::TICKET_CLOSE)
            <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data" id="replyForm">
                @csrf
                <div style="margin-bottom: 12px;">
                    <textarea name="message" class="form-textarea" rows="4" placeholder="Write your reply..." required>{{ old('message') }}</textarea>
                </div>

                <button type="button" class="add-file-btn" id="addFileBtn">
                    <i class="fas fa-plus"></i> Add Attachment
                </button>
                <p class="file-hint">
                    <i class="fas fa-info-circle"></i> Max 5 files | Allowed: .jpg, .jpeg, .png, .pdf, .doc, .docx
                </p>
                <div id="fileUploadsContainer"></div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-reply"></i> Reply
                </button>
            </form>
        @else
            <div style="text-align: center; padding: 20px; color: var(--text-muted);">
                <i class="fas fa-lock" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                This ticket is closed. No further replies can be made.
            </div>
        @endif
    </div>

    <!-- PREVIOUS REPLIES -->
    <div class="section-title">Previous Replies</div>

    @forelse($messages as $message)
        <div class="reply-card">
            <div class="reply-header">
                <div class="reply-sender">
                    @if($message->admin_id == 0)
                        {{ $message->ticket->name }} <small>(Customer)</small>
                    @else
                        {{ $message->admin->name }} <small>(Staff)</small>
                    @endif
                </div>
                <div class="reply-time">
                    <i class="far fa-clock"></i> {{ showDateTime($message->created_at, 'd M, Y @ H:i') }}
                </div>
            </div>
            <div class="reply-message">{{ $message->message }}</div>

            @if($message->attachments->count() > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 12px;">
                    @foreach($message->attachments as $k => $image)
                        <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="attachment-link">
                            <i class="far fa-file-alt"></i> File {{ $k + 1 }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <img src="{{ asset('assets/images/empty_list.png') }}" alt="empty" onerror="this.style.display='none'">
            <i class="fas fa-comments" style="font-size: 40px; color: #d1d5db; display: block; margin-bottom: 12px;"></i>
            <h5>No replies found!</h5>
        </div>
    @endforelse

</div>

<!-- CLOSE CONFIRMATION MODAL -->
<div id="closeModal" class="modal-overlay hidden">
    <div class="modal-box">
        <h3>Close Ticket</h3>
        <p>Are you sure you want to close this ticket?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeCloseModal()">@lang('Cancel')</button>
            <form action="{{ route('ticket.close', $myTicket->id) }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="modal-btn modal-btn-confirm" style="width: 100%;">Close Ticket</button>
            </form>
        </div>
    </div>
</div>

<!-- BOTTOM NAVIGATION -->
<div class="bottom-nav-container">
    <div class="bottom-nav">
        <a href="{{ route('user.home') }}" class="nav-item">
            <i class="fas fa-home"></i>
            <span>@lang('Home')</span>
        </a>
        <a href="{{ route('user.promotions') }}" class="nav-item">
            <i class="fas fa-gift"></i>
            <span>@lang('Promotion')</span>
        </a>
        <a href="{{ route('user.referrals') }}" class="nav-item center-item">
            <div class="center-icon-circle"><i class="fas fa-share-nodes"></i></div>
            <span>@lang('Invite')</span>
        </a>
        <a href="{{ route('user.redeem.index') }}" class="nav-item">
            <i class="fas fa-trophy"></i>
            <span>@lang('Reward')</span>
        </a>
        <a href="{{ route('user.account') }}" class="nav-item">
            <i class="fas fa-user-circle"></i>
            <span>@lang('Member')</span>
        </a>
    </div>
</div>

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
        btn.closest('.removeFileInput').remove();
    }

    function openCloseModal() {
        document.getElementById('closeModal').classList.remove('hidden');
    }

    function closeCloseModal() {
        document.getElementById('closeModal').classList.add('hidden');
    }
</script>

@endsection
