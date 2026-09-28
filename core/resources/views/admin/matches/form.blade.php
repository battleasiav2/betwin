@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ $match->id ? route('admin.match.update', $match->id) : route('admin.match.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Game')</label>
                                <input type="text" name="game_name" class="form-control" required value="{{ old('game_name', $match->game_name) }}" placeholder="PUBG MOBILE">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>@lang('Match title')</label>
                                <input type="text" name="title" class="form-control" required value="{{ old('title', $match->title) }}" placeholder="PUBG Premium Squad Live">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Spots filled')</label>
                                <input type="number" name="spots_filled" class="form-control" min="0" required value="{{ old('spots_filled', $match->spots_filled ?? 0) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Spots total')</label>
                                <input type="number" name="spots_total" class="form-control" min="1" required value="{{ old('spots_total', $match->spots_total ?? 100) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Entry')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="entry_fee" class="form-control" min="0" required value="{{ old('entry_fee', $match->entry_fee ?? 0) }}">
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Prize')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="prize" class="form-control" min="0" required value="{{ old('prize', $match->prize ?? 0) }}">
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Sort')</label>
                                <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $match->sort_order ?? 0) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Live badge')</label>
                                <input type="hidden" name="is_live" value="0">
                                <input type="checkbox" name="is_live" value="1" data-width="100%" data-size="large" data-on="@lang('Live')" data-off="@lang('Offline')" data-onstyle="-success" data-offstyle="-danger" @checked(old('is_live', $match->is_live ?? true))>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Show on home')</label>
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" value="1" data-width="100%" data-size="large" data-on="@lang('Show')" data-off="@lang('Hide')" data-onstyle="-success" data-offstyle="-danger" @checked(old('status', $match->status ?? true))>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.match.index') }}" />
@endpush
