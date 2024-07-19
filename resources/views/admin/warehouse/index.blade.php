@extends('layouts.master')
@section('title')
    {{ __('Warehouse') }}
@endsection

@section('main-content')
    <style>
        .action-btn {
            width: 29px;
            height: 28px;
            border-radius: 9.3552px;
            color: #fff;
            display: inline-table;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
    </style>
    @include('admin.section.flash_message')
    <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item">{{ __('Warehouse') }}</li>
        </ol>
        <div class="float-end">
            <a href="{{ route('admin.warehouse.create') }}" data-size="lg" data-url="{{ route('admin.warehouse.create') }}"
                data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                data-title="{{ __('Create Warehouse') }}" class="btn btn-sm btn-primary">
                Add
            </a>
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
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('City') }}</th>
                                    <th>{{ __('Zip Code') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouses as $warehouse)
                                    <tr class="font-style">
                                        <td>{{ $warehouse->name }}</td>
                                        <td>{{ $warehouse->address }}</td>
                                        <td>{{ $warehouse->city }}</td>
                                        <td>{{ $warehouse->city_zip }}</td>

                                        {{-- @if (Gate::check('show warehouse') || Gate::check('edit warehouse') || Gate::check('delete warehouse')) --}}
                                        <td class="Action">
                                            {{-- @can('show warehouse') --}}
                                            <div class="action-btn bg-warning">
                                                <a href="{{ route('admin.warehouse.show', $warehouse->id) }}"
                                                    class="btn btn-sm d-inline-flex align-items-center"
                                                    data-bs-toggle="tooltip" title="{{ __('View') }}"><i
                                                        class="link-icon" data-feather="eye"></i></a>
                                            </div>
                                            {{-- @endcan --}}
                                            {{-- @can('edit warehouse') --}}
                                            <div class="action-btn bg-info">
                                                <a href="{{ route('admin.warehouse.edit', $warehouse->id) }}" class="btn btn-sm  align-items-center" data-bs-toggle="tooltip"
                                                    title="{{ __('Edit') }}" data-title="{{ __('Edit Warehouse') }}">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </div>
                                            {{-- @endcan --}}
                                            {{-- @can('delete warehouse') --}}
                                            <div class="action-btn bg-danger">
                                                <a href="{{ route('admin.warehouse.destroy', $warehouse->id) }}" class="btn btn-sm  align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                        class="link-icon" data-feather="trash"></i></a>
                                            </div>
                                            {{-- @endcan --}}
                                        </td>
                                        {{-- @endif --}}
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
