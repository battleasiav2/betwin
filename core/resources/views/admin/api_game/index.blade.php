@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('RapidVerse API Credentials')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.api.game.settings') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('API URL')</label>
                                    <input type="url" name="api_url" class="form-control" required
                                           value="{{ old('api_url', $apiSettings->api_url ?? 'https://www.rapidverse.site/api/demo') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Agent Username')</label>
                                    <input type="text" name="agent_user" class="form-control"
                                           value="{{ old('agent_user', $apiSettings->agent_user ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Currency')</label>
                                    <input type="text" name="currency" class="form-control"
                                           value="{{ old('currency', $apiSettings->currency ?? 'BDT') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('API Token')</label>
                                    <input type="password" name="api_token" class="form-control" required
                                           value="{{ old('api_token', $apiSettings->api_token ?? '') }}"
                                           autocomplete="new-password">
                                    <small class="text-muted">@lang('Do not share. Rotate if leaked.')</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Secret Key')</label>
                                    <input type="password" name="secret_key" class="form-control" required
                                           value="{{ old('secret_key', $apiSettings->secret_key ?? '') }}"
                                           autocomplete="new-password">
                                    <small class="text-muted">@lang('Callback requires X-Secret-Key header')</small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Callback URL')</label>
                                    <input type="url" name="callback_url" class="form-control" required
                                           value="{{ old('callback_url', $apiSettings->callback_url ?? 'https://bet369win.com/callback.php') }}">
                                    <small class="text-muted">@lang('RapidVerse panel e ei same callback set thakte hobe')</small>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary">@lang('Save API Settings')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Game Provider Status')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Game Provider')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($apiGames as $game)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ __($game->name) }}</span>
                                </td>
                                <td>
                                    @if($game->status == 1)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @elseif($game->status == 2)
                                        <span class="badge badge--warning">@lang('Maintenance')</span>
                                    @elseif($game->status == 3)
                                        <span class="badge badge--info">@lang('Coming Soon')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('Disabled')</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline--primary editBtn"
                                            data-id="{{ $game->id }}"
                                            data-name="{{ $game->name }}"
                                            data-status="{{ $game->status }}">
                                        <i class="la la-pencil"></i> @lang('Manage')
                                    </button>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage ?? 'No data') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Manage API Game'): <span class="provider-name"></span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST" id="editForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Select Status')</label>
                            <select name="status" class="form-control" required>
                                <option value="1">@lang('চালু আছে')</option>
                                <option value="2">@lang('কাজ চলছে')</option>
                                <option value="3">@lang('শীঘ্রই আসছে')</option>
                                <option value="0">@lang('বন্ধ')</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100">@lang('Update Status')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($){
            "use strict";
            $('.editBtn').on('click', function() {
                var modal = $('#editModal');
                var id = $(this).data('id');
                var name = $(this).data('name');
                var status = $(this).data('status');

                modal.find('.provider-name').text(name);
                modal.find('select[name=status]').val(status);
                $('#editForm').attr('action', '{{ route("admin.api.game.update", "") }}/' + id);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
