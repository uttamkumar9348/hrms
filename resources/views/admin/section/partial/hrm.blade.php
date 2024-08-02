<li
    class="nav-item {{ request()->routeIs('admin.company.*') ||
    request()->routeIs('admin.branch.*') ||
    request()->routeIs('admin.departments.*') ||
    request()->routeIs('admin.posts.*') || 
    request()->routeIs('admin.users.*') || 
    request()->routeIs('admin.logout-requests.*') || 
    request()->routeIs('admin.attendances.*') ||
    request()->routeIs('admin.regularization.*')
        ? 'active'
        : '' }}">
    <a data-href="#" class="nav-link" data-bs-toggle="collapse" href="#hrms" role="button" aria-expanded="false"
        aria-controls="company">
        <i class="link-icon" data-feather="users"></i>
        <span class="link-title">{{ __('HRM') }}</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>

    <div class="{{ request()->routeIs('admin.company.*') ||
        request()->routeIs('admin.branch.*') ||
        request()->routeIs('admin.departments.*') ||
        request()->routeIs('admin.posts.*') || 
        request()->routeIs('admin.users.*') || 
        request()->routeIs('admin.logout-requests.*') || 
        request()->routeIs('admin.attendances.*') ||
        request()->routeIs('admin.regularization.*')
            ? ''
            : 'collapse' }}" id="hrms">
        <ul class="nav sub-menu">
            @include('admin.section.partial.company')
            @include('admin.section.partial.user')
            @include('admin.section.partial.attendance')
            @include('admin.section.partial.client')
            @include('admin.section.partial.projectManagement')
            @include('admin.section.partial.taskManagement')
            @include('admin.section.partial.holiday')
            @include('admin.section.partial.notice')
            @include('admin.section.partial.payroll')
            @include('admin.section.partial.leave')
            @include('admin.section.partial.team-meeting')
            @include('admin.section.partial.tada')
            @include('admin.section.partial.shiftManagement')
            @include('admin.section.partial.assetManagement')
            @include('admin.section.partial.staticPageContent')
            @include('admin.section.partial.ticket')
            @include('admin.section.partial.setting')
            @include('admin.section.partial.regularization')
        </ul>
    </div>
</li>
