@extends('templates.sunfyre.layouts.master')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
        height: 100vh;
        background: #000;
    }
    
    .game-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: #000;
    }
    
    .loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #064e46;
        border-top: 3px solid #2563eb;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loading-text {
        color: #2563eb;
        font-size: 14px;
    }
    
    .game-iframe {
        width: 100%;
        height: 100%;
        border: none;
        background: #000;
        display: none;
    }
    
    .game-iframe.show {
        display: block;
    }
</style>

<div class="game-container">
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <div class="loading-text">Loading Game...</div>
    </div>
    
    <iframe id="gameIframe" class="game-iframe" src="{{ $game_url }}" allowfullscreen></iframe>
</div>

<script>
    const iframe = document.getElementById('gameIframe');
    const loading = document.getElementById('loading');
    
    iframe.onload = function() {
        loading.style.display = 'none';
        iframe.classList.add('show');
    };
</script>
@endsection
