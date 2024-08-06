@canany(['list_leave_type', 'list_leave_request'])
    <li
        class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.leaves.*') ||
        request()->routeIs('admin.time-leave-request.*') ||
        request()->routeIs('admin.leave-request.*')
            ? 'active'
            : '' }} " href="{{ route('admin.leaves.index') }}" aria-controls="leaves">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Leave</span>
        </a>
        {{--        <div class="{{ request()->routeIs('admin.leaves.*') || --}}
        {{--                    request()->routeIs('admin.leave-request.*') ?'' : 'collapse'  }}" id="leaves"> --}}
        {{--            <ul class="nav sub-menu"> --}}

        {{--                @can('list_leave_type') --}}
        {{--                    <li class="nav-item"> --}}
        {{--                        <a --}}
        {{--                            href="{{route('admin.leaves.index')}}" --}}
        {{--                            data-href="{{route('admin.leaves.index')}}" --}}
        {{--                            class="nav-link {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }}">Leave Type</a> --}}
        {{--                    </li> --}}
        {{--                @endcan --}}

        {{--                @can('list_leave_request') --}}
        {{--                    <li class="nav-item"> --}}
        {{--                        <a href="{{route('admin.leave-request.index')}}" --}}
        {{--                           data-href="{{route('admin.leave-request.index')}}" --}}
        {{--                           class="nav-link {{ request()->routeIs('admin.leave-request.*') ? 'active' : '' }}">Leave --}}
        {{--                            Request</a> --}}
        {{--                    </li> --}}
        {{--                @endcan --}}
        {{--                @can('create_leave_request') --}}
        {{--                    <li class="nav-item"> --}}
        {{--                        <a href="{{route('admin.leave-request.add')}}" --}}
        {{--                           data-href="{{route('admin.leave-request.add')}}" --}}
        {{--                           class="nav-link {{ request()->routeIs('admin.leave-request.add') ? 'active' : '' }}">Create --}}
        {{--                            Leave Request</a> --}}
        {{--                    </li> --}}
        {{--                @endcan --}}
        {{--                @can('time_leave_list') --}}
        {{--                    <li class="nav-item"> --}}
        {{--                        <a href="{{route('admin.time-leave-request.index')}}" --}}
        {{--                           data-href="{{route('admin.time-leave-request.index')}}" --}}
        {{--                           class="nav-link {{ request()->routeIs('admin.time-leave-request.*') ? 'active' : '' }}">Time --}}
        {{--                            Leave Request</a> --}}
        {{--                    </li> --}}
        {{--                @endcan --}}
        {{--                @can('time_leave_list') --}}
        {{--                    <li class="nav-item"> --}}
        {{--                        <a href="{{route('admin.time-leave-request.create')}}" --}}
        {{--                           data-href="{{route('admin.time-leave-request.create')}}" --}}
        {{--                           class="nav-link {{ request()->routeIs('admin.time-leave-request.create') ? 'active' : '' }}">Create --}}
        {{--                            Time Leave Request</a> --}}
        {{--                    </li> --}}
        {{--                @endcan --}}

        {{--            </ul> --}}
        {{--        </div> --}}
    </li>
@endcanany
