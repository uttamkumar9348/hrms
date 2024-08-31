@can('manage-content_management')
    <li class="nav-item">
        <a href="{{ route('admin.static-page-contents.index') }}"
           data-href="{{ route('admin.static-page-contents.index') }}" class="nav-link {{ request()->routeIs('admin.static-page-contents.*') ? 'active' : '' }}">
            Content Management
        </a>
    </li>
@endcan
