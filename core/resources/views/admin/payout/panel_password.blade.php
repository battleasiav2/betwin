@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Payout Security')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payout.auth.check') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="font-weight-bold">
                                @lang('Payout Panel Password') <span class="text--danger">*</span>
                            </label>
                            <input type="password"
                                   name="panel_password"
                                   class="form-control"
                                   maxlength="6"
                                   minlength="6"
                                   required>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn--primary w-100">
                                @lang('Continue to Payout')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection