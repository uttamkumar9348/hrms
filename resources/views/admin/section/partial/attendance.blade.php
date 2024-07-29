<!-- @can('list_attendance')
    <li class="nav-item {{ request()->routeIs('admin.attendances.*')  ? 'active' : '' }}">
        <a
            href="{{ route('admin.attendances.index') }}"
            data-href="{{ route('admin.attendances.index') }}"
            class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Attendance Section</span>
        </a>
    </li>
@endcan -->


<li class="nav-item  {{ request()->routeIs('admin.attendances.*')  ? 'active' : '' }}   ">
    <a data-href="#" class="nav-link" data-bs-toggle="collapse" href="#attendance_management" role="button" aria-expanded="false" aria-controls="company">
    <i class="link-icon" data-feather="calendar"></i>
        <span class="link-title">Attendance Section</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>

    <div class="{{request()->routeIs('admin.attendances.*') ||
                            request()->routeIs('admin.logout-requests.*')?'' : 'collapse'  }}" id="attendance_management">
        <ul class="nav sub-menu">
            <!-- @can('list_employee') -->
            <li class="nav-item">
                <a href="{{route('admin.attendances.index')}}" data-href="{{route('admin.attendances.index')}}" class="nav-link {{request()->routeIs('admin.users.*') ? 'active' : ''}}">Attendance</a>
            </li>
            <!-- @endcan -->

            <!-- @can('list_logout_request') -->
            <li class="nav-item">
                <a href="{{route('admin.regularization.index')}}" data-href="{{route('admin.regularization.index')}}" class="nav-link {{request()->routeIs('admin.regularization.index') ? 'active' : ''}}">Regularization</a>
            </li>
            <!-- @endcan -->
        </ul>
    </div>
</li>