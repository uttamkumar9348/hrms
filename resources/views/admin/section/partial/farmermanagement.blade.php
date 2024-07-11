<li class="nav-item  {{ request()->routeIs('farmer*') ? 'active' : '' }}   ">
    <a data-href="#" class="nav-link" data-bs-toggle="collapse" href="#farmermanagement" role="button" aria-expanded="false"
        aria-controls="company">
        <i class="link-icon" data-feather="users"></i>
        <span class="link-title">{{ __('Farmer Mgmt.') }}</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>

    <div class="{{ request()->routeIs('farmer*') ? '' : 'collapse' }}" id="farmermanagement">
        <ul class="nav sub-menu">
            <li class="nav-item">
                <a href="{{ route('farmer.farming_registration.index') }}"
                    data-href="{{ route('farmer.farming_registration.index') }}"
                    class="nav-link {{ request()->routeIs('farmer/farming_registration*') ? 'active' : '' }}">{{ __('Farmer Registration') }}</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('farmer.guarantor.index') }}" data-href="{{ route('farmer.guarantor.index') }}"
                    class="nav-link {{ request()->routeIs('farmer/guarantor*') ? 'active' : '' }}">{{ __('Farmer Guarantor') }}</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('farmer.guarantor.index') }}" data-href="{{ route('farmer.guarantor.index') }}"
                    class="nav-link {{ request()->routeIs('farmer/guarantor*') ? 'active' : '' }}">{{ __('Farmer Guarantor') }}</a>
            </li>
            <li class="nav-item {{ Request::is('farmer/payment*') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.payment.index') }}">{{ __('Security Deposit') }}</a>
            </li>
            <li class="nav-item {{ Request::is('farmer/allotment*') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.allotment.index') }}">{{ __('Allotment') }}</a>
            </li>
            <li class="nav-item {{ Request::is('farmer/reimbursement*') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.reimbursement.index') }}">{{ __('Reimbursement') }}</a>
            </li>
            <li class="nav-item {{ Request::is('farmer/farming_detail*') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.farming_detail.index') }}">{{ __('Farmer Detail') }}</a>
            </li>
            <li class="nav-item {{ Request::is('farmer/cutting_order*') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('farmer.cutting_order.index') }}">{{ __('Issue Cutting Order') }}</a>
            </li>
        </ul>
    </div>
</li>
