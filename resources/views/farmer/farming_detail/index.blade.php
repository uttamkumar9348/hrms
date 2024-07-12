@extends('layouts.master')
@section('title')
    {{ __('Farmer Detail') }}
@endsection
@section('scripts')
@endsection
@section('main-content')
    @include('admin.section.flash_message')

    <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Farmer Detail') }}</li>
        </ol>

        <div class="float-end">
            {{-- @can('create farmer detail') --}}
            <a href="{{ route('admin.farmer.farming_detail.create') }}" class="btn btn-sm btn-primary">
                Add
            </a>
            {{-- @endcan --}}
        </div>
    </nav>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Farmer') }}</th>
                                    <!-- <th>{{ __('Name') }}</th> -->
                                    <th>{{ __('Plot Number') }}</th>
                                    <!-- <th>{{ __('Kata Number') }}</th> -->
                                    <th>{{ __('Area in Acar') }}</th>
                                    <th>{{ __('Date of Harvesting') }}</th>
                                    <!-- <th>{{ __('Quantity') }}</th> -->
                                    <th>{{ __('Tentative Harvest Quantity') }}</th>
                                    <th>{{ __('Seed Category') }}</th>
                                    <th>{{ __('Cutting Order') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($farming_details as $farming_detail)
                                    <tr class="font-style">
                                        <td>{{ @$farming_detail->farming->name }}</td>
                                        <!-- <td>{{ $farming_detail->name }}</td> -->
                                        <td>{{ $farming_detail->plot_number }}</td>
                                        <!-- <td>{{ $farming_detail->kata_number }}</td> -->
                                        <td>{{ $farming_detail->area_in_acar }}</td>
                                        <td>{{ $farming_detail->date_of_harvesting }}</td>
                                        <!-- <td>{{ $farming_detail->quantity }}</td> -->
                                        <td>{{ $farming_detail->tentative_harvest_quantity }}</td>
                                        <td>{{ @$farming_detail->seed_category->name }}</td>
                                        <td>
                                            @if (@$farming_detail->is_cutting_order)
                                                <span
                                                    class="status_badge text-capitalize badge bg-success p-2 px-3 rounded">Yes</span>
                                            @else
                                                <span
                                                    class="status_badge text-capitalize badge bg-danger p-2 px-3 rounded">No</span>
                                            @endif
                                        </td>
                                        {{-- <td>
                                        @if (@$farming_detail->is_validate)
                                        <span class="status_badge text-capitalize badge bg-success p-2 px-3 rounded">Validated</span>
                                        @else 
                                        <span class="status_badge text-capitalize badge bg-danger p-2 px-3 rounded">Not Validated</span>
                                        @endif
                                    </td> --}}
                                        <td class="Action">
                                            @if ($farming_detail->is_cutting_order != '1')
                                                @can('edit farmer detail')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('admin.farmer.farming_detail.edit', $farming_detail->id) }}"
                                                            class="mx-3 btn btn-sm  align-items-center">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete farmer detail')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['farmer.farming_detail.destroy', $farming_detail->id],
                                                            'id' => 'delete-form-' . $farming_detail->id,
                                                        ]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                class="ti ti-trash text-white"></i></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            @else
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
