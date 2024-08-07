@can('list_content')
    <li class="nav-item">
        <a href="{{ route('admin.static-page-contents.index') }}"
           data-href="{{ route('admin.static-page-contents.index') }}" class="nav-link {{ request()->routeIs('admin.static-page-contents.*') ? 'active' : '' }}">
            <i class="link-icon" data-feather="airplay"></i>
            <span class="link-title">Content Management</span>
        </a>
    </li>
@endcan
