@extends($activeTemplate . 'layouts.app')

@section('app')
@php
    $title = $policy->data_values->title ?? 'Terms & Conditions';
    $details = $policy->data_values->details ?? '';
@endphp

<style>
    .policy-page {
        max-width: 720px;
        margin: 0 auto;
        padding: 16px 16px 96px;
        min-height: 70vh;
    }
    .policy-top {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }
    .policy-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #d5e4f7;
        color: #123b66;
        text-decoration: none !important;
        font-size: 16px;
    }
    .policy-card {
        background: #ffffff;
        border: 1px solid #e8f0fa;
        border-radius: 16px;
        padding: 22px 18px 28px;
        box-shadow: 0 8px 24px rgba(18, 59, 102, 0.06);
    }
    .policy-title {
        margin: 0 0 16px;
        font-size: 22px;
        font-weight: 800;
        color: #123b66;
        line-height: 1.3;
    }
    .policy-body {
        color: #374151;
        font-size: 14px;
        line-height: 1.7;
    }
    .policy-body h1,
    .policy-body h2,
    .policy-body h3,
    .policy-body h4 {
        color: #123b66;
        margin: 18px 0 8px;
        font-weight: 800;
    }
    .policy-body p { margin: 0 0 12px; }
    .policy-body ul,
    .policy-body ol {
        padding-left: 18px;
        margin: 0 0 12px;
    }
    .policy-body a { color: #2563eb; }
</style>

<div class="policy-page">
    <div class="policy-top">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="policy-back" aria-label="@lang('Back')">
            <i class="fas fa-chevron-left"></i>
        </a>
        <div style="flex:1"></div>
    </div>

    <article class="policy-card">
        <h1 class="policy-title">{{ __($title) }}</h1>
        <div class="policy-body">
            {!! $details !!}
        </div>
    </article>
</div>
@endsection
