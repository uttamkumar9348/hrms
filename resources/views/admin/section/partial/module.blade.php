
@can('manage-modules')
    <li class="nav-item">
        <a
            href="{{ route('admin.modules.index') }}"
            data-href="{{ route('admin.modules.index') }}"
            class="nav-link {{ request()->routeIs('admin.modules.*')  ? 'active' : '' }}">
            <i class="link-icon" data-feather="help-circle"></i>
            <span class="link-title">Modules</span>
        </a>
    </li>
@endcan
