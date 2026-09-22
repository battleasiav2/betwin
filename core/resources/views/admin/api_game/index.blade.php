@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
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