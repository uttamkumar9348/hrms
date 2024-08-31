<div class="table-responsive  mt-2">
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
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $loan)
                @php
                    $loan_category_id = json_decode($loan->loan_category_id);
                    $loan_type_id = json_decode($loan->loan_type_id);
                    $price_kg = json_decode($loan->price_kg);
                    $quantity = json_decode($loan->quantity);
                    $total_amount = json_decode($loan->total_amount);
                    $count = count($loan_category_id);
                @endphp
                <tr class="font-style">
                    <td>{{ @$loan->farming->name }}</td>
                    <td>{{ $loan->registration_number }}</td>
                    <td>{{ $loan->agreement_number }}</td>
                    <td>{{ $loan->date }}</td>
                    <td>
                        @for ($i = 0; $i < $count; $i++)
                            @php
                                $productcategory = App\Models\ProductServiceCategory::where(
                                    'id',
                                    $loan_category_id[$i],
                                )->first();
                            @endphp
                            {{ $productcategory->name }}
                            @if($i < $count - 1),@endif
                        @endfor
                    </td>

                    <td>
                        @for ($i = 0; $i < $count; $i++)
                            @php
                                $product = App\Models\ProductService::where(
                                    'id',
                                    $loan_type_id[$i],
                                )->first();
                            @endphp
                            {{ $product->name }}
                            @if($i < $count - 1),@endif
                        @endfor
                    </td>
                    <td>
                        @for ($i = 0; $i < $count; $i++)
                            {{ $price_kg[$i] }}@if($i < $count - 1),@endif
                        @endfor
                    </td>
                    <td>
                        @for ($i = 0; $i < $count; $i++)
                            {{ $quantity[$i] }}
                            @if($i < $count - 1),@endif
                        @endfor
                    </td>
                    <td>
                        @for ($i = 0; $i < $count; $i++)
                            {{ $total_amount[$i] }}
                            @if($i < $count - 1),@endif
                        @endfor
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
