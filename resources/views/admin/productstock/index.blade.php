@extends('layouts.master')
@section('title')
    {{ __('Manage Product Stock') }}
@endsection

@section('main-content')
    @include('admin.section.flash_message')
    <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item">{{ __('Product Stock') }}</li>
        </ol>
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
                                    <th>{{ __('Sku') }}</th>
                                    <th>{{ __('Current Quantity') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productServices as $productService)
                                    <tr class="font-style">
                                        <td>{{ $productService->name }}</td>
                                        <td>{{ $productService->sku }}</td>
                                        <td>{{ $productService->quantity }}</td>

                                        <td class="Action">
                                            <div>
                                                <a data-size="md" href="#"
                                                    class="btn btn-primary"
                                                    data-url="{{ route('admin.productstock.edit', $productService->id) }}"
                                                    data-ajax-popup="true" data-size="xl" data-bs-toggle="tooltip"
                                                    title="{{ __('Update Quantity') }}">
                                                    Add
                                                </a>
                                            </div>
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
