@extends('layouts.master')
@section('title')
    {{ __('Farmer Guarantor') }}
@endsection

@section('main-content')
    @include('admin.section.flash_message')
    <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item">{{ __('Farmer Guarantor') }}</li>
        </ol>
        <div class="float-end">
            {{-- @can('create farmer guarantor') --}}
                <a href="{{ route('admin.farmer.guarantor.create') }}" class="btn btn-sm btn-primary">
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
                                    <th>{{ __('Farmer Name') }}</th>
                                    <th>{{ __('Registration No.') }}</th>
                                    <th>{{ __('Age') }}</th>
                                    <th>{{ __('Father Name') }}</th>
                                    <th>{{ __('Post Office') }}</th>
                                    <th>{{ __('Police Station') }}</th>
                                    <th>{{ __('District') }}</th>
                                    <th>{{ __('Block') }}</th>
                                    <th>{{ __('Village') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guarantors as $guarantor)
                                    <tr class="font-style">
                                        <td>{{ $guarantor->naming->name }}</td>
                                        <td>{{ @$guarantor->farming->name }}</td>
                                        <td>{{ $guarantor->registration_number }}</td>
                                        <td>{{ $guarantor->age }}</td>
                                        <td>{{ $guarantor->father_name }}</td>
                                        <td>{{ $guarantor->post_office }}</td>
                                        <td>{{ $guarantor->police_station }}</td>
                                        <td>{{ @$guarantor->district->name }}</td>
                                        <td>{{ @$guarantor->block->name }}</td>
                                        <td>{{ @$guarantor->village->name }}</td>

                                        <td class="Action">
                                            @can('edit farmer guarantor')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="{{ route('admin.farmer.guarantor.edit', $guarantor->id) }}"
                                                        class="mx-3 btn btn-sm  align-items-center">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('delete farmer guarantor')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['farmer.guarantor.destroy', $guarantor->id],
                                                        'id' => 'delete-form-' . $guarantor->id,
                                                    ]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                            class="ti ti-trash text-white"></i></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
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
