@extends('layouts.master')
@section('title')
    {{ __('Farmer Allotments') }}
@endsection

@section('main-content')
    @include('admin.section.flash_message')
    <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item">{{ __('Seeds,Fertiliser & Pesticides Allotment') }}</li>
        </ol>
        <div class="float-end">
            {{-- @can('create farmer loan') --}}
                <a href="{{ route('admin.farmer.loan.create') }}" class="btn btn-primary">
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
                                    <th>{{ __('Farmer Name') }}</th>
                                    <th>{{ __('Registration No.') }}</th>
                                    <th>{{ __('Agreement No') }}</th>
                                    <th>{{ __('Date of Agreement') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $loan)
                                    <tr class="font-style">
                                        <td>{{ $loan->farming->name }}</td>
                                        <td>{{ $loan->registration_number }}</td>
                                        <td>{{ $loan->agreement_number }}</td>
                                        <td>{{ $loan->date }}</td>
                                        <td>{{ $loan->category->name }}</td>
                                        <td>{{ $loan->type->name }}</td>
                                        <td>{{ $loan->price_kg }}</td>
                                        <td>{{ $loan->quantity }}</td>
                                        <td>{{ $loan->total_amount }}</td>

                                        <td class="Action">
                                            <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                            {{-- @can('edit farmer loan') --}}
                                                <li class="me-2">
                                                    <a href="{{ route('admin.farmer.loan.edit', $loan->id) }}">
                                                        <i class="link-icon" data-feather="edit"></i>
                                                    </a>
                                                </li>
                                            {{-- @endcan --}}
                                            {{-- @can('delete farmer loan') --}}
                                                <li>
                                                    <a class="deleteBtn"
                                                        data-href="{{ route('admin.farmer.loan.destroy', $loan->id) }}"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                        <i class="link-icon" data-feather="delete"></i>
                                                    </a>
                                                </li>
                                            {{-- @endcan --}}
                                            </ul>
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
