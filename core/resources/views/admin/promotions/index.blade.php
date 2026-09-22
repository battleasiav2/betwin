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
                                <th>@lang('Banner')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Min Deposit')</th>
                                <th>@lang('Max Bonus')</th>
                                <th>@lang('Bonus %')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promotions as $promotion)
                            <tr>
                                <td>
                                    <div class="user">
                                        <div class="thumb">
                                            <img src="{{ getImage('assets/images/promotion/'. $promotion->image) }}" alt="@lang('image')">
                                        </div>
                                    </div>
                                </td>
                                <td>{{ __($promotion->title) }}</td>
                                <td>{{ showAmount($promotion->min_limit) }}</td>
                                <td>{{ showAmount($promotion->max_bonus) }}</td>
                                <td>{{ getAmount($promotion->bonus_percent) }}%</td>
                                <td>
                                    @if($promotion->status == 1)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.promotion.edit', $promotion->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="la la-pencil"></i> @lang('Edit')
                                        </a>
                                        <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.promotion.delete', $promotion->id) }}" data-question="@lang('Are you sure to delete this promotion?')">
                                            <i class="la la-trash"></i> @lang('Delete')
                                        </button>
                                    </div>
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
            @if ($promotions->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($promotions) }}
            </div>
            @endif
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.promotion.create') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add New')</a>
@endpush