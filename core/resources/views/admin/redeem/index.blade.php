@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Used/Limit')</th>
                                    <th>@lang('Per User Limit')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($redeems as $r)
                                    <tr>
                                        <td><span class="fw-bold">{{ $r->code }}</span></td>
                                        <td>
                                            @if($r->type == 1)
                                                <span class="text--success">{{ showAmount($r->amount) }} {{ gs('cur_sym') }}</span>
                                            @else
                                                <span class="text--primary">{{ getAmount($r->amount) }}% (Split)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-pill bg--info">{{ $r->used_count }} / {{ $r->user_limit }}</span>
                                        </td>
                                        <td>
                                            {{ $r->user_redeem_limit > 1000 ? 'Unlimited' : $r->user_redeem_limit . ' Times' }}
                                        </td>
                                        <td>
                                            @php echo $r->status ? '<span class="badge badge--success">Active</span>' : '<span class="badge badge--danger">Inactive</span>' @endphp
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--{{ $r->status ? 'danger' : 'success' }} confirmationBtn" 
                                                data-action="{{ route('admin.redeem.status', $r->id) }}" 
                                                data-question="Are you sure to {{ $r->status ? 'disable' : 'enable' }} this code?">
                                                <i class="la la-{{ $r->status ? 'eye-slash' : 'eye' }}"></i> {{ $r->status ? 'Disable' : 'Enable' }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Generate New Redeem Code')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.redeem.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Calculation Type')</label>
                            <select name="type" class="form-control" required>
                                <option value="1">@lang('Fixed Amount')</option>
                                <option value="2">@lang('Percent')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Total Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" required>
                                <span class="input-group-text">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('User Limit ')</label>
                            <input type="number" name="user_limit" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Per User Limit ')</label>
                            <select name="user_redeem_limit" class="form-control" required>
                                <option value="1">1 @lang('Time')</option>
                                <option value="2">2 @lang('Times')</option>
                                <option value="5">5 @lang('Times')</option>
                                <option value="unlimited">@lang('Unlimited')</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Generate Code')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-sm btn-outline--primary btn--shadow addBtn">
        <i class="las la-plus"></i> @lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        (function($){
            "use strict";
            $('.addBtn').on('click', function() {
                var modal = $('#addModal');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush