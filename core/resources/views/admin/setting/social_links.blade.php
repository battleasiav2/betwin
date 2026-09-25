@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.setting.social.links') }}">
                        @csrf
                        <div class="form-group">
                            <label>@lang('WhatsApp Link')</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="lab la-whatsapp"></i></span>
                                <input type="url" class="form-control" name="whatsapp"
                                       value="{{ old('whatsapp', @$links->whatsapp) }}"
                                       placeholder="https://wa.me/8801XXXXXXXXX">
                            </div>
                            <small class="text-muted">@lang('Example'): https://wa.me/8801XXXXXXXXX</small>
                        </div>

                        <div class="form-group">
                            <label>@lang('Telegram Link')</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="lab la-telegram"></i></span>
                                <input type="url" class="form-control" name="telegram"
                                       value="{{ old('telegram', @$links->telegram) }}"
                                       placeholder="https://t.me/yourusername">
                            </div>
                            <small class="text-muted">@lang('Example'): https://t.me/yourusername</small>
                        </div>

                        <div class="form-group">
                            <label>@lang('Facebook Link')</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="lab la-facebook-f"></i></span>
                                <input type="url" class="form-control" name="facebook"
                                       value="{{ old('facebook', @$links->facebook) }}"
                                       placeholder="https://facebook.com/yourpage">
                            </div>
                            <small class="text-muted">@lang('Example'): https://facebook.com/yourpage</small>
                        </div>

                        <div class="form-group">
                            <label>@lang('Live Support Link')</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="las la-headset"></i></span>
                                <input type="text" class="form-control" name="support"
                                       value="{{ old('support', @$links->support) }}"
                                       placeholder="https://tawk.to/... or any support URL">
                            </div>
                            <small class="text-muted">@lang('Optional floating support button URL')</small>
                        </div>

                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Save Changes')</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">@lang('Note')</h5>
                    <p class="mb-2">@lang('These links appear on the floating social buttons and footer across the site.')</p>
                    <p class="mb-0 text-muted">@lang('Leave a field empty to hide that button.')</p>
                </div>
            </div>
        </div>
    </div>
@endsection
