@extends('layouts.master')
@section('title','Salary Group')
@section('sub_page','Lists')
@section('page')
    <a href="{{ route('admin.overtime.index')}}">
        OverTime
    </a>
@endsection

@section('main-content')
    <section class="content">
        @include('admin.section.flash_message')

        @include('admin.payrollSetting.common.breadcrumb')
        <div class="row">
            <div class="col-lg-2">
                @include('admin.payrollSetting.common.setting_menu')
            </div>
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <div class="justify-content-end">
                            @can('add-overtime')
                                <a class="btn btn-success"
                                   href="{{ route('admin.overtime.create')}}">
                                    <i class="link-icon" data-feather="plus"></i> Add OverTime
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th class="text-center">Max. Daily OT</th>
                                    <th class="text-center">Pay Percent/Rate</th>
                                    <th class="text-center">Is Active</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @forelse($overTimeData as $ot)
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $ot->title ?? $ot->id }}<br>
                                            <small>Employee Count : {{ ($ot->ot_employees_count) }}</small>
                                        </td>
                                        <td class="text-center"> {{ $ot->max_daily_ot_hours }} Hour</td>
                                        <td class="text-center">{{ $ot->overtime_pay_rate ? $currency . $ot->overtime_pay_rate : $ot->pay_percent. '%' }}</td>
                                        <td class="text-center">
                                            <label class="switch">
                                                <input class="toggleStatus" href="{{route('admin.overtime.toggle-status',$ot->id)}}"
                                                       type="checkbox"{{($ot->is_active) == 1 ?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                                @can('edit-overtime')
                                                    <li class="me-2">
                                                        <a href="{{route('admin.overtime.edit',$ot->id)}}"
                                                            data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                                            <i class="link-icon" data-feather="edit"></i>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete-overtime')
                                                    <li>
                                                        <a class="delete" href="#"
                                                           data-href="{{route('admin.overtime.delete',$ot->id)}}"
                                                           title="Delete">
                                                            <i class="link-icon" data-feather="delete"></i>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">
                                            <p class="text-center"><b>No records found!</b></p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    @include('admin.payrollSetting.overtime.common.scripts')
@endsection






