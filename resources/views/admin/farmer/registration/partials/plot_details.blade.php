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
            @foreach ($plot as $plot_detail)
                <tr class="font-style">
                    <td>{{ @$plot_detail->farming->name }}</td>
                    <td>{{ $plot_detail->plot_number }}</td>
                    <td>{{ $plot_detail->area_in_acar }}</td>
                    <td>{{ $plot_detail->date_of_harvesting }}</td>
                    <td>{{ $plot_detail->tentative_harvest_quantity }}</td>
                    <td>{{ @$plot_detail->seed_category->name }}</td>
                    <td>
                        @if (@$plot_detail->is_cutting_order)
                            <span class="status_badge text-capitalize badge bg-success p-2 px-3 rounded">Yes</span>
                        @else
                            <span class="status_badge text-capitalize badge bg-danger p-2 px-3 rounded">No</span>
                        @endif
                    </td>
                    <td class="Action">
                        <ul class="d-flex list-unstyled mb-0 justify-content-center">
                            @if ($plot_detail->is_cutting_order != '1')
                                @can('edit-plot')
                                    <li class="me-2">
                                        <a href="{{ route('admin.farmer.farming_detail.edit', $plot_detail->id) }}"
                                            data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                            <i class="link-icon" data-feather="edit"></i>
                                        </a>
                                    </li>
                                @endcan
                                @can('delete-plot')
                                    <li>
                                        <a class="deleteBtn"
                                            data-href="{{ route('admin.farmer.farming_detail.destroy', $plot_detail->id) }}"
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
