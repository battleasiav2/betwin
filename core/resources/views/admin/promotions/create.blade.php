@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ route('admin.promotion.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Title')</label>
                                <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Action Link Status')</label>
                                <select name="is_link" class="form-control">
                                    <option value="1">@lang('On Claim Button')</option>
                                    <option value="0">@lang('Off Claim Button')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Show in Deposit Page')</label>
                                <select name="show_deposit" class="form-control">
                                    <option value="1">@lang('Yes')</option>
                                    <option value="0">@lang('No')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Action Link URL')</label>
                                <input type="text" name="link" class="form-control" value="{{ old('link') }}" placeholder="R Lab BD">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Bonus Percentage') (%)</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="bonus_percent" class="form-control" required value="{{ old('bonus_percent') }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Turnover Multiplier') (x)</label>
                                <div class="input-group">
                                    <input type="number" name="turnover_multiplier" class="form-control" required value="{{ old('turnover_multiplier') }}">
                                    <span class="input-group-text">@lang('×')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Minimum Deposit Limit')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="min_limit" class="form-control" required value="{{ old('min_limit') }}">
                                    <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Maximum Bonus Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="max_bonus" class="form-control" required value="{{ old('max_bonus') }}">
                                    <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Banner Image')</label>
                                <input type="file" name="image" class="form-control" accept=".png, .jpg, .jpeg" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Description')</label>
                                <textarea name="description" rows="8" class="form-control nicEdit">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Save Promotion')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection