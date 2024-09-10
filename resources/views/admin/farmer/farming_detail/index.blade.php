@extends('layouts.master')
@section('title')
    {{ __('Plot Detail') }}
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#loss_reason').hide();
            $('#loss_area').hide();

            $('#reportmodal').on('click', function() {
                $('#reportModal').modal('show');
            });
            $('.close_btn').on('click', function() {
                $('#reportModal').modal('hide');
            });
            $('input[name=croploss]').on('click',function(){
                var is_crop_loss = $(this).val();
                console.log(is_crop_loss);
                if(is_crop_loss == "Yes")
                {
                    $('#loss_reason').show();
                    $('#loss_area').show();
                } 
                else if(is_crop_loss == "No")
                {
                    $('#loss_reason').hide();
                    $('#loss_area').hide();
                }
            });
        });
    </script>
@endsection
@section('main-content')
    @include('admin.section.flash_message')

    <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Plot') }}</li>
        </ol>

        <div class="float-end">
            @can('create-plot')
                <a href="{{ route('admin.farmer.farming_detail.create') }}" class="btn btn-sm btn-primary">
                    Add
                </a>
            @endcan
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
                                    <th>{{ __('Plot Number') }}</th>
                                    <th>{{ __('Area in Acar') }}</th>
                                    <th>{{ __('Date of Planting') }}</th>
                                    <th>{{ __('Tentative Plant Quantity') }}</th>
                                    <th>{{ __('Seed Category') }}</th>
                                    <th>{{ __('Cutting Order') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($farming_details as $farming_detail)
                                    <tr class="font-style">
                                        <td>{{ @$farming_detail->farming->name }}</td>
                                        <td>{{ $farming_detail->plot_number }}</td>
                                        <td>{{ $farming_detail->area_in_acar }}</td>
                                        <td>{{ $farming_detail->date_of_harvesting }}</td>
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
                                        <td class="Action">
                                            <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                                @if ($farming_detail->is_cutting_order != '1')
                                                    @can('edit-plot')
                                                        <li class="me-2">
                                                            <a href="{{ route('admin.farmer.farming_detail.edit', $farming_detail->id) }}"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                                                <i class="link-icon" data-feather="edit"></i>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    <li class="me-2">
                                                        <a href="#" data-bs-toggle="tooltip"
                                                            title="{{ __('Report') }}" id="reportmodal">
                                                            <i class="link-icon" data-feather="file-text"></i>
                                                        </a>
                                                    </li>
                                                    @can('delete-plot')
                                                        <li>
                                                            <a class="deleteBtn" href="#"
                                                                data-href="{{ route('admin.farmer.farming_detail.destroy', $farming_detail->id) }}"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                                <i class="link-icon" data-feather="delete"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </li>
                                                    @endcan
                                                @else
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
    <!-- Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Servey Report</h5>
                    <button type="button" class="close close_btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Farmer Name: </p>
                    <p>Plot No: </p>
                    <p>Area: </p>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <p>Is there any crop loss</p>
                            <input name="croploss" type="radio" value="Yes"> Yes
                            <input name="croploss" type="radio" value="No"> No
                        </div>
                        <div class="form-group col-md-6" id="loss_reason">
                            {{ Form::label('loss_reason', __('Loss Reason'), ['class' => 'form-label']) }}
                            <select class="form-control select" name="loss_reason" id="loss_reason" placeholder="Select">
                                <option value="">{{ __('Select Reason') }}</option>
                                <option value="Flood">Flood</option>
                                <option value="Insect">Insect</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="loss_area">
                            {{ Form::label('loss_area', __('Loss Area (Acr.)'), ['class' => 'form-label']) }} <br>
                            <input name="loss_area" type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('total_planting_area', __('Total Area for final planting'), ['class' => 'form-label']) }} <br>
                            <input name="total_planting_area" type="text" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('tentative_harvest_quantity', __('Tentative Plant Quantity (In Ton)'), ['class' => 'form-label']) }}
                            {{ Form::number('tentative_harvest_quantity', '', ['class' => 'form-control', 'required' => 'required']) }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close_btn" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
