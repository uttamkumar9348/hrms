
@canany(['view_query_list'])
    <li class="nav-item">
        <a
            href="{{ route('admin.supports.index') }}"
            data-href="{{ route('admin.supports.index') }}"
            class="nav-link {{ request()->routeIs('admin.supports.*')  ? 'active' : '' }}">
            <i class="link-icon" data-feather="help-circle"></i>
            <span class="link-title">Support</span>
        </a>
    </li>
@endcan
