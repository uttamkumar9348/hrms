@extends('layouts.master')

@section('title', 'Employee Payroll Generate Increment')

@section('action', 'Payroll Generate')

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.payroll.employeeSalary.common.breadcrumb')

        <div class="card mb-4">
            <div class="card-body pb-0">
                <h4 class="mb-4"> Payroll Create</h4>
                <form class="forms-sample" action="{{ route('admin.employee-salary.payroll') }}" method="get">

                    <div class="payroll-fil border-bottom">
                        <div class="row">
                            <div class="col-lg col-md-6 mb-4">
                                <h5 class="mb-2">Branch</h5>
                                <select class="form-select form-select-lg" name="branch_id" id="branch_id">
                                    <option value="" {{ !isset($filterData['branch_id']) ? 'selected' : '' }}>Select
                                        Branch</option>
                                    @foreach ($branches as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($filterData['branch_id']) && $value->id == $filterData['branch_id'] ? 'selected' : '' }}>
                                            {{ ucfirst($value->name) }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg col-md-6 mb-4">
                                <h5 class="mb-2">Department</h5>
                                <select class="form-select " name="department_id" id="department_id">
                                    <option selected disabled>Select Department</option>
                                </select>
                            </div>
                            <div class="col-lg col-md-6 mb-4">
                                <h5 class="mb-2">Select Year</h5>
                                @if ($isBSDate)
                                    <select class="form-select form-select" name="year" id="year">
                                        @for ($i = 0; $i <= 4; $i++)
                                            <option
                                                {{ ($filterData['year'] ?? $currentNepaliYearMonth['year']) == $currentNepaliYearMonth['year'] - $i ? 'selected' : '' }}
                                                value="{{ $currentNepaliYearMonth['year'] - $i }}">
                                                {{ $currentNepaliYearMonth['year'] - $i }}</option>
                                        @endfor
                                    </select>
                                @else
                                    <select class="form-select form-select" name="year" id="year">
                                        @foreach (range(date('Y'), date('Y') - 5, -1) as $year)
                                            <option {{ ($filterData['year'] ?? date('Y')) == $year ? 'selected' : '' }}
                                                value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                @endif

                            </div>

                            <div class="col-lg col-md-6 mb-4">
                                <h5 class="mb-2">Salary Cycle</h5>
                                <select class="form-select form-select" name="salary_cycle" id="salary_cycle">
                                    @foreach ($salaryCycles as $value)
                                        <option @if (isset($filterData['salary_cycle']) && $filterData['salary_cycle'] == $value) selected @endif
                                            value="{{ $value }}">{{ ucfirst($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg col-md-6 mb-4 @if ((isset($filterData['salary_cycle']) && $filterData['salary_cycle'] == 'weekly') || old('salary_cycle') == 'weekly') d-none @endif"
                                id="monthDiv">
                                <h5 class="mb-2">Salary Month</h5>

                                <select class="form-select form-select" name="month" id="month">
                                    @foreach ($months as $key => $value)
                                        <option
                                            {{ ($filterData['month'] ?? ($isBSDate ? $currentNepaliYearMonth['month'] - 1 : date('m') - 1)) == $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg col-md-6 mb-4 @if (isset($filterData['salary_cycle']) && $filterData['salary_cycle'] == 'weekly')  @endif d-none"
                                id="weekDiv">
                                <h5 class="mb-2">Select Week</h5>
                                <select class="form-select form-select" name="week" id="week">

                                    <option selected disabled>Select Week</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class=" row payroll-check pt-4 d-flex justify-content-between align-items-center">
                        <div class="col-lg col-md-3 mb-4 form-check">
                            <input type="checkbox"
                                {{ isset($filterData['include_tada']) && $filterData['include_tada'] == 1 ? 'checked' : '' }}
                                name="include_tada" value="1" id="include_tada">
                            <label class="form-check-label" for="includeTada">
                                Include TADA
                            </label>
                        </div>
                        <div class="col-lg col-md-3 mb-4 form-check">
                            <input type="checkbox"
                                {{ isset($filterData['include_advance_salary']) && $filterData['include_advance_salary'] == 1 ? 'checked' : '' }}
                                name="include_advance_salary" value="1" id="advance_salary">
                            <label class="form-check-label" for="advanceSalary">
                                Include Advance Salary
                            </label>
                        </div>
                        <div class="col-lg col-md-3 mb-4 form-check">
                            <input type="checkbox" checked value="1" name="attendance" id="use_attendance">
                            <label class="form-check-label" for="">
                                Use Attendance
                            </label>
                        </div>

                        <div class="col-lg col-md-3 mb-4 form-check">
                            <div class="float-md-end">
                                @can('create-payroll')
                                    <button type="submit" onclick="filterPayroll()"
                                        class="btn btn-primary me-md-2">Generate</button>
                                @endcan
                                <a href="{{ route('admin.employee-salary.payroll') }}" class="btn btn-warning">Clear</a>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row payroll-fil">

            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">Report:</h5>
                        <p> Monthly Payroll Summary</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">Duration</h5>
                        <p>{{ $payrolls['payrollSummary']['duration'] }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">Total Basic Salary:</h5>
                        <p> {{ $payrolls['payrollSummary']['totalBasicSalary'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">

                        <h5 class="mb-2">Total Net Salary:</h5>
                        <p> {{ $payrolls['payrollSummary']['totalNetSalary'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">Total Allowance:</h5>
                        <p> {{ $payrolls['payrollSummary']['totalAllowance'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">Total Deduction:</h5>
                        <p> {{ $payrolls['payrollSummary']['totalDeduction'] }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">Total Overtime:</h5>
                        <p> {{ $payrolls['payrollSummary']['totalOverTime'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">Total Undertime:</h5>
                        <p> {{ $payrolls['payrollSummary']['otherPayment'] }}</p>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <section>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th class="text-center">Net Salary</th>
                                <th class="text-center">Duration</th>
                                <th class="text-center">Paid On</th>
                                <th class="text-center">Paid By</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls['employeeSalary'] as $payroll)
                                <tr
                                    class="alert alert-{{ $payroll['status'] == \App\Enum\PayslipStatusEnum::generated->value ? 'success' : ($payroll['status'] == \App\Enum\PayslipStatusEnum::review->value ? 'warning' : ($payroll['status'] == \App\Enum\PayslipStatusEnum::locked->value ? 'danger' : 'primary')) }}">
                                    <td>#</td>
                                    <td>{{ $payroll['employee_name'] }}</td>
                                    <td class="text-center">{{ $currency . ' ' . $payroll['net_salary'] }}</td>
                                    <td class="text-center">
                                        @if (isset($payroll['salary_cycle']) && $payroll['salary_cycle'] == 'monthly')
                                            {{ \App\Helpers\AppHelper::getMonthYear($payroll['salary_from']) }}
                                        @else
                                            {{ \App\Helpers\AttendanceHelper::payslipDate($payroll['salary_from']) }} to
                                            {{ \App\Helpers\AttendanceHelper::payslipDate($payroll['salary_to']) }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ isset($payroll['paid_on']) ? \App\Helpers\AttendanceHelper::paidDate($payroll['paid_on']) : '-' }}
                                    </td>
                                    <td class="text-center">{{ $payroll['paid_by'] ?? '-' }}</td>
                                    <td class="text-center">{{ ucfirst($payroll['status']) }}</td>
                                    <td class="text-center">
                                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown"
                                            role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false" title="More Action">
                                        </a>

                                        <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                                            <ul class="list-unstyled p-1 mb-0">
                                                @can('show-payroll')
                                                    <li class="dropdown-item py-2">
                                                        <a
                                                            href="{{ route('admin.employee-salary.payroll-detail', $payroll['id']) }}">
                                                            <button class="btn btn-primary btn-xs">View
                                                            </button>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit-payroll')
                                                    <li class="dropdown-item py-2">
                                                        <a
                                                            href="{{ route('admin.employee-salary.payroll-edit', $payroll['id']) }}">
                                                            <button class="btn btn-primary btn-xs">Edit</button>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete-payroll')
                                                    @if ($payroll['status'] == \App\Enum\PayslipStatusEnum::generated->value)
                                                        <li class="dropdown-item py-2">
                                                            <form
                                                                action="{{ route('admin.employee-salary.payroll-delete', $payroll['id']) }}"
                                                                method="post">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-primary btn-xs">Delete</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                @endcan
                                                @if ($payroll['status'] == \App\Enum\PayslipStatusEnum::generated->value)
                                                    <li class="dropdown-item py-2">
                                                        <a href="" class="makePayment"
                                                            data-href="{{ route('admin.employee-salaries.make_payment', $payroll['id']) }}"
                                                            title="Make Payment">
                                                            <button class="btn btn-primary btn-xs">Pay</button>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
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
    </section>

    @include('admin.payroll.employeeSalary.common.payment')
@endsection

@section('scripts')
    @include('admin.payroll.employeeSalary.common.scripts')

    <script>
        //     payment model
        $('body').on('click', '.makePayment', function(event) {
            event.preventDefault();
            let url = $(this).data('href');

            $('#payrollPayment').attr('action', url)
            $('#paymentForm').modal('show');
        });

        $('#payrollPayment').submit(function(event) {
            event.preventDefault(); // Prevent default form submission
            if (!validateForm()) {
                return false; // Exit if validation fails
            }
            // Serialize form data
            let formData = $(this).serialize();

            // Send AJAX request
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData,
                success: function(response) {
                    // Check if there are any errors
                    if (response.success) {
                        // If successful, close the modal
                        $('#paymentForm').modal('hide');
                        // Optionally, perform any additional actions such as refreshing the page
                        location.reload(); // Example: Refresh the page
                    } else {
                        // If there are errors, display them within the modal
                        let errorsHtml = '<div class="alert alert-danger"><ul>';
                        $.each(response.errors, function(key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        errorsHtml += '</ul></div>';
                        $('#modal-errors').html(errorsHtml);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        function validateForm() {
            // Perform your validation here
            let isValid = true;

            // Example validation: Check if payment method is selected
            if ($('#payment_method_id').val() === null) {
                isValid = false;
                // Display error message
                $('#modal-errors').html('<div class="alert alert-danger">Please select a payment method.</div>');
            }

            // You can add more validation rules as needed

            return isValid;
        }

        $('#salary_cycle').on('click', function() {
            let cycle = $(this).val();
            if (cycle === 'monthly') {
                $('#weekDiv').addClass('d-none');
                $('#monthDiv').removeClass('d-none');
            } else {
                $('#weekDiv').removeClass('d-none');
                $('#monthDiv').addClass('d-none');
            }
        });

        $('#salary_cycle').change(function() {
            let cycle = $('#salary_cycle option:selected').val();
            let selectedYear = $('#year option:selected').val();
            let week = "{{ $filterData['week'] ?? '' }}";

            $('#week').empty();
            if (cycle === 'weekly') {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('admin/employee-salaries/getWeeks') }}" + '/' + selectedYear,
                }).done(function(response) {
                    if (!week) {
                        $('#week').append('<option disabled  selected >Select Week</option>');
                    }
                    response.data.forEach(function(data) {
                        $('#week').append('<option ' + ((data.week_value === week) ? "selected" :
                                '') + ' value="' + data.week_value + '" >' + data.week +
                            '</option>');
                    });
                });
            }

        }).trigger('change');
    </script>
    </script>

    <script>
        $('#branch_id').change(function() {
            let selectedBranchId = $('#branch_id option:selected').val();

            let departmentId = "{{ $filterData['department_id'] ?? '' }}";
            $('#department_id').empty();
            if (selectedBranchId) {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('admin/departments/get-All-Departments') }}" + '/' + selectedBranchId,
                }).done(function(response) {
                    if (!departmentId) {
                        $('#department_id').append(
                        '<option disabled  selected >Select Department</option>');
                    }
                    response.data.forEach(function(data) {
                        $('#department_id').append('<option ' + ((data.id == departmentId) ?
                                "selected" : '') + ' value="' + data.id + '" >' + data
                            .dept_name + '</option>');
                    });
                });
            }
        }).trigger('change');
    </script>
@endsection
