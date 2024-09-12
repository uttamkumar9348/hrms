@extends('layouts.master')
@section('title')
    {{ __('Bank Details Create') }}
@endsection
@section('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('input[type=radio][name="finance_category"]').on('change', function(event) {
                var value = $(this).val();
                if (value == "Non-loan") {
                    $('.finance_category_fields').hide();
                    $('.coperative_fields').hide();
                    $('.bank_detail_fields').hide();
                    $('.non_loan_fields').show();
                } else {
                    $('.finance_category_fields').show();
                    $('.non_loan_fields').hide();
                }
            });
            $('#loan_type').on('change', function(event) {
                var value = $(this).val();
                if (value == "Bank") {
                    $('.coperative_fields').hide();
                    $('.bank_detail_fields').show();
                } else {
                    $('.bank_detail_fields').hide();
                    $('.coperative_fields').show();
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
            <li class="breadcrumb-item"><a href="{{ route('admin.farmer.bank_details.index') }}">{{ __('Bank Details') }}</a>
            </li>
            <li class="breadcrumb-item">{{ __('Create') }}</li>
        </ol>
    </nav>

    <div class="row">
        {{ Form::open(['url' => 'admin/farmer/bank_details', 'class' => 'w-100']) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="hidden" name="created_by" id="created_by" value="{{ Auth::user()->id }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('farming_id', __('Select Farmer'), ['class' => 'form-label']) }}
                                <select class="form-control select" name="farming_id" id="farming_id" required
                                    placeholder="Select Farmer">
                                    <option value="">{{ __('Select Farmer') }}</option>
                                    @foreach ($farmings as $farming)
                                        <option value="{{ $farming->id }}">{{ $farming->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            {{ Form::label('finance_category', __('Finance Category'), ['class' => 'form-label']) }}
                            <br>
                            <input type="radio" name="finance_category" value="Loan"> Loan
                            <input type="radio" name="finance_category" value="Non-loan"> Non-loan
                        </div>
                        <div class="col-md-6 finance_category_fields" style="display:none;">
                            <div class="form-group">
                                {{ Form::label('loan_type', __('Loan Type'), ['class' => 'form-label']) }}
                                <select class="form-control select" name="loan_type" id="loan_type"
                                    placeholder="Select Loan Type">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Co-Operative">Co-Operative</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 bank_detail_fields" style="display:none;">
                            <div class="form-group">
                                {{ Form::label('bank', __('Bank'), ['class' => 'form-label']) }}
                                <select class="form-control select" name="bank" id="bank" placeholder="Select Bank">
                                    <option value="">{{ __('Select Bank') }}</option>
                                    <option value="State Bank of India (SBI)">State Bank of India (SBI)</option>
                                    <option value="Punjab National Bank (PNB)">Punjab National Bank (PNB)</option>
                                    <option value="Bank of Baroda (BOB)">Bank of Baroda (BOB)</option>
                                    <option value="Canara Bank">Canara Bank</option>
                                    <option value="Union Bank of India">Union Bank of India</option>
                                    <option value="HDFC Bank">HDFC Bank</option>
                                    <option value="ICICI Bank">ICICI Bank</option>
                                    <option value="Axis Bank">Axis Bank</option>
                                    <option value="Kotak Mahindra Bank">Kotak Mahindra Bank</option>
                                    <option value="IndusInd Bank">IndusInd Bank</option>
                                    <option value="Yes Bank">Yes Bank</option>
                                    <option value="IDBI Bank">IDBI Bank</option>
                                    <option value="Central Bank of India">Central Bank of India</option>
                                    <option value="Indian Bank">Indian Bank</option>
                                    <option value="Bank of India">Bank of India</option>
                                    <option value="Oriental Bank of Commerce (OBC)">Oriental Bank of Commerce (OBC)</option>
                                    <option value="Corporation Bank">Corporation Bank</option>
                                    <option value="Andhra Bank">Andhra Bank</option>
                                    <option value="Allahabad Bank">Allahabad Bank</option>
                                    <option value="Syndicate Bank">Syndicate Bank</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6 bank_detail_fields" style="display:none;">
                            {{ Form::label('account_number', __('Loan Account Number'), ['class' => 'form-label']) }}
                            {{ Form::text('account_number', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-6 bank_detail_fields" style="display:none;">
                            {{ Form::label('ifsc_code', __('IFSC Code'), ['class' => 'form-label']) }}
                            {{ Form::text('ifsc_code', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-6 bank_detail_fields" style="display:none;">
                            {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                            {{ Form::text('branch', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-6 coperative_fields" style="display:none;">
                            {{ Form::label('name_of_cooperative', __('Co-Operative Name'), ['class' => 'form-label']) }}
                            {{ Form::text('name_of_cooperative', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-6 coperative_fields" style="display:none;">
                            {{ Form::label('cooperative_address', __('Co-Operative Branch'), ['class' => 'form-label']) }}
                            {{ Form::text('cooperative_address', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="col-md-6 non_loan_fields" style="display:none;">
                            <div class="form-group">
                                {{ Form::label('bank', __('Bank'), ['class' => 'form-label']) }}
                                <select class="form-control select" name="non_loan_bank" id="bank" placeholder="Select Bank">
                                    <option value="">{{ __('Select Bank') }}</option>
                                    <option value="State Bank of India (SBI)">State Bank of India (SBI)</option>
                                    <option value="Punjab National Bank (PNB)">Punjab National Bank (PNB)</option>
                                    <option value="Bank of Baroda (BOB)">Bank of Baroda (BOB)</option>
                                    <option value="Canara Bank">Canara Bank</option>
                                    <option value="Union Bank of India">Union Bank of India</option>
                                    <option value="HDFC Bank">HDFC Bank</option>
                                    <option value="ICICI Bank">ICICI Bank</option>
                                    <option value="Axis Bank">Axis Bank</option>
                                    <option value="Kotak Mahindra Bank">Kotak Mahindra Bank</option>
                                    <option value="IndusInd Bank">IndusInd Bank</option>
                                    <option value="Yes Bank">Yes Bank</option>
                                    <option value="IDBI Bank">IDBI Bank</option>
                                    <option value="Central Bank of India">Central Bank of India</option>
                                    <option value="Indian Bank">Indian Bank</option>
                                    <option value="Bank of India">Bank of India</option>
                                    <option value="Oriental Bank of Commerce (OBC)">Oriental Bank of Commerce (OBC)</option>
                                    <option value="Corporation Bank">Corporation Bank</option>
                                    <option value="Andhra Bank">Andhra Bank</option>
                                    <option value="Allahabad Bank">Allahabad Bank</option>
                                    <option value="Syndicate Bank">Syndicate Bank</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6 non_loan_fields" style="display:none;">
                            {{ Form::label('account_number', __('Saving Account Number'), ['class' => 'form-label']) }}
                            {{ Form::text('non_loan_account_number', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-6 non_loan_fields" style="display:none;">
                            {{ Form::label('ifsc_code', __('IFSC Code'), ['class' => 'form-label']) }}
                            {{ Form::text('non_loan_ifsc_code', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-6 non_loan_fields" style="display:none;">
                            {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                            {{ Form::text('non_loan_branch', '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="button" value="{{ __('Cancel') }}"
                    onclick="location.href = '{{ route('admin.farmer.farming_detail.index') }}';" class="btn btn-light">
                <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
