<li
    class="nav-item {{ Request::segment(1) == 'warehouse' || Request::segment(1) == 'purchase' || Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' || Request::route()->getName() == 'pos.show' ? 'active' : '' }} ">
    <a data-href="#" class="nav-link" data-bs-toggle="collapse" href="#farmermanagement" role="button" aria-expanded="false"
        aria-controls="company">
        <i class="link-icon" data-feather="users"></i>
        <span class="link-title">{{ __('Inventory') }}</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>

    <div class="{{ Request::segment(1) == 'warehouse' || Request::segment(1) == 'purchase' || Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' || Request::route()->getName() == 'pos.show' ? '' : 'collapse' }}"
        id="farmermanagement">
        <ul class="nav sub-menu {{ Request::segment(1) == 'warehouse' || Request::segment(1) == 'purchase' || Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' || Request::route()->getName() == 'pos.show' ? 'show' : '' }}">
            <li class="nav-item">
                <a href="{{ route('admin.farmer.farming_registration.index') }}"
                    class="nav-link {{ request()->routeIs('admin.farmer.farming_registration*') ? 'active' : '' }}">{{ __('Farmer Registration') }}</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.farmer.guarantor.index') }}"
                    class="nav-link {{ request()->routeIs('admin.farmer.guarantor*') ? 'active' : '' }}">{{ __('Farmer Guarantor') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/farmer/payment*') ? ' active' : '' }}"
                    href="{{ route('admin.farmer.payment.index') }}">{{ __('Security Deposit') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/farmer/allotment*') ? ' active' : '' }}"
                    href="{{ route('admin.farmer.allotment.index') }}">{{ __('Allotment') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/farmer/reimbursement*') ? ' active' : '' }}"
                    href="{{ route('admin.farmer.reimbursement.index') }}">{{ __('Reimbursement') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/farmer/farming_detail*') ? ' active' : '' }}"
                    href="{{ route('admin.farmer.farming_detail.index') }}">{{ __('Farmer Detail') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/farmer/cutting_order*') ? ' active' : '' }}"
                    href="{{ route('admin.farmer.cutting_order.index') }}">{{ __('Issue Cutting Order') }}</a>
            </li>
        </ul>
        <ul
            class="nav sub-menu {{ Request::segment(1) == 'warehouse' || Request::segment(1) == 'purchase' || Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' || Request::route()->getName() == 'pos.show' ? 'show' : '' }}">
            {{-- @can('manage warehouse') --}}
            <li
                class="nav-item {{ Request::route()->getName() == 'warehouse.index' || Request::route()->getName() == 'warehouse.show' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.warehouse.index') }}">{{ __('Warehouse') }}</a>
            </li>
            {{-- @endcan --}}
            {{-- @can('manage purchase') --}}
            <li
                class="nav-item {{ Request::route()->getName() == 'purchase.index' || Request::route()->getName() == 'purchase.create' || Request::route()->getName() == 'purchase.edit' || Request::route()->getName() == 'purchase.show' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.purchase.index') }}">{{ __('Purchase') }}</a>
            </li>
            {{-- @endcan --}}

            {{-- @can('manage warehouse') --}}
            <li
                class="nav-item {{ Request::route()->getName() == 'warehouse-transfer.index' || Request::route()->getName() == 'warehouse-transfer.show' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.warehouse-transfer.index') }}">{{ __('Transfer') }}</a>
            </li>
            {{-- @endcan --}}
            {{-- @can('create barcode') --}}
            <li
                class="nav-item {{ Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.pos.barcode') }}">{{ __('Print Barcode') }}</a>
            </li>
            {{-- @endcan --}}
            {{-- @can('manage pos') --}}
            <li class="nav-item {{ Request::route()->getName() == 'pos-print-setting' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.pos.print.setting') }}">{{ __('Print Settings') }}</a>
            </li>
            {{-- @endcan --}}
        </ul>
    </div>
</li>
