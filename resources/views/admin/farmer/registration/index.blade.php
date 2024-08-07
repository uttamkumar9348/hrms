@extends('layouts.master')
@section('title')
    {{ __('Farmer Registration') }}
@endsection

@section('main-content')
    @include('admin.section.flash_message')
    <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item">{{ __('Farmer Registration') }}</li>
        </ol>
        <div class="float-end">
            {{-- @can('create farmer registration') --}}
                <a href="{{ route('admin.farmer.farming_registration.create') }}" class="btn btn-sm btn-primary">
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
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('G. Code') }}</th>
                                    <th>{{ __('Mobile') }}</th>
                                    <th>{{ __('Age') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('Qualification') }}</th>
                                    <th>{{ __('State') }}</th>
                                    <th>{{ __('District') }}</th>
                                    <th>{{ __('Block') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($farmings as $farming)
                                    <tr class="font-style">
                                        <td>{{ $farming->name }}</td>
                                        <td>{{ $farming->g_code }}</td>
                                        <td>{{ $farming->mobile }}</td>
                                        <td>{{ $farming->age }}</td>
                                        <td>{{ $farming->gender }}</td>
                                        <td>{{ $farming->qualification }}</td>
                                        <td>{{ @$farming->state->name }}</td>
                                        <td>{{ @$farming->district->name }}</td>
                                        <td>{{ @$farming->block->name }}</td>
                                        <td>
                                            @if (@$farming->is_validate)
                                                <span
                                                    class="status_badge text-capitalize badge bg-success p-2 px-3 rounded">Validated</span>
                                            @else
                                                <span
                                                    class="status_badge text-capitalize badge bg-danger p-2 px-3 rounded">Not
                                                    Validated</span>
                                            @endif
                                        </td>
                                        <td class="Action">
                                            <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                            @if (@$farming->is_validate)
                                                @can('show farmer registration')
                                                    <li class="me-2">
                                                        <a href="{{ route('admin.farmer.farming_registration.show', $farming->id) }}">
                                                            <i class="link-icon" data-feather="eye"></i>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @else
                                                @can('validate farmer registration')
                                                    @if ($farming->created_by != Auth::user()->id)
                                                        <li class="me-2">
                                                            <a href="{{ route('admin.farmer.farming_registration.validate', $farming->id) }}"
                                                                data-bs-toggle="tooltip" title="{{ __('Validate') }}">
                                                                <i class="link-icon" data-feather="check-square"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endcan
                                                @can('edit farmer registration')
                                                    <li class="me-2">
                                                        <a href="{{ route('admin.farmer.farming_registration.edit', $farming->id) }}">
                                                            <i class="link-icon" data-feather="edit"></i>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete farmer registration')
                                                    <li>
                                                        <a class="deleteBtn" data-href="{{route('admin.farmer.farming_registration.destroy', $farming->id)}}"
                                                            data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                            <i class="link-icon" data-feather="delete"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </li>
                                                @endcan
                                            @endif
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
