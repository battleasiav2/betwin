@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">

            <div class="card b-radius--10">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">@lang('Instant Payout')</h5>
                    <span class="badge badge--primary">@lang('Merchant Panel')</span>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.payout.send') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        @lang('Channel') <span class="text--danger">*</span>
                                    </label>
                                    <select name="channel" class="form-control" required>
                                        <option value="">@lang('Select One')</option>
                                        <option value="BKASH" {{ old('channel') == 'BKASH' ? 'selected' : '' }}>BKASH</option>
                                        <option value="NAGAD" {{ old('channel') == 'NAGAD' ? 'selected' : '' }}>NAGAD</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        @lang('Account Number') <span class="text--danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="account"
                                        class="form-control"
                                        value="{{ old('account') }}"
                                        placeholder="@lang('e.g. 01XXXXXXXXX')"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        @lang('Amount (BDT)') <span class="text--danger">*</span>
                                    </label>
                                    <div class="input-group amount-input-group">
                                        <input
                                            type="number"
                                            step="0.01"
                                            min="1"
                                            name="amount"
                                            class="form-control"
                                            value="{{ old('amount') }}"
                                            placeholder="@lang('Enter amount')"
                                            required
                                        >
                                        <div class="input-group-append">
                                            <span class="input-group-text">@lang('BDT')</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        @lang('Beneficiary Name')
                                        <span class="text-muted small">(@lang('optional'))</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="user_name"
                                        class="form-control"
                                        value="{{ old('user_name') }}"
                                        placeholder="@lang('Name of receiver (optional)')"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        @lang('Security PIN') <span class="text--danger">*</span>
                                    </label>
                                    <input
                                        type="password"
                                        name="security_pin"
                                        class="form-control"
                                        maxlength="4"
                                        minlength="4"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn--primary w-100">
                                <i class="las la-paper-plane"></i>
                                @lang('Withdraw Now')
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('api_raw'))
                <div class="card b-radius--10 mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">@lang('Last API Raw Response')</h6>
                    </div>
                    <div class="card-body">
                        <pre class="small mb-0" style="white-space: pre-wrap; word-break: break-all;">
{{ session('api_raw') }}
                        </pre>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <style>
        .amount-input-group .input-group-text{
            display: flex;
            align-items: center;
            height: 100%;
        }
    </style>
@endsection