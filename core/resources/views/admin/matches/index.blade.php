@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Game')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Spots')</th>
                                <th>@lang('Entry')</th>
                                <th>@lang('Prize')</th>
                                <th>@lang('Live')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matches as $match)
                            <tr>
                                <td>{{ $match->game_name }}</td>
                                <td>{{ $match->title }}</td>
                                <td>{{ $match->spots_filled }} / {{ $match->spots_total }}</td>
                                <td>{{ showAmount($match->entry_fee) }}</td>
                                <td>{{ showAmount($match->prize) }}</td>
                                <td>
                                    @if($match->is_live)
                                        <span class="badge badge--success">@lang('Live')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Offline')</span>
                                    @endif
                                </td>
                                <td>
                                    @if($match->status)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('Hidden')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.match.edit', $match->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="la la-pencil"></i> @lang('Edit')
                                        </a>
                                        <button class="btn btn-sm btn-outline--{{ $match->status ? 'warning' : 'success' }} confirmationBtn" data-action="{{ route('admin.match.status', $match->id) }}" data-question="@lang('Change this match status?')">
                                            <i class="la la-eye"></i> {{ $match->status ? __('Hide') : __('Show') }}
                                        </button>
                                        <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.match.delete', $match->id) }}" data-question="@lang('Delete this match?')">
                                            <i class="la la-trash"></i> @lang('Delete')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">@lang('No matches yet')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($matches->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($matches) }}
            </div>
            @endif
        </div>
    </div>
</div>
<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.match.create') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add Match')</a>
@endpush
