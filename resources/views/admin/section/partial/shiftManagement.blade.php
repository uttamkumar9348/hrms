
@canany(['manage-office_time'])
    <li class="nav-item  {{ request()->routeIs('admin.office-times.*')  ? 'active' : '' }}    ">
        <a class="nav-link"   data-href="#" data-bs-toggle="collapse" href="#shiftManagement" role="button" aria-expanded="false" aria-controls="shiftManagment">
            <i class="link-icon" data-feather="book"></i>
            <span class="link-title"> Shift Management </span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="{{ request()->routeIs('admin.office-times.*') ?'' : 'collapse'  }} " id="shiftManagement">
             <ul class="nav sub-menu">

                 @can('manage-office_time')
                    <li class="nav-item">
                        <a href="{{route('admin.office-times.index')}}"
                           data-href="{{route('admin.office-times.index')}}"
                           class="nav-link {{request()->routeIs('admin.office-times.*') ? 'active' : ''}}">Office Time</a>
                    </li>
                 @endcan

            </ul>
        </div>
    </li>
@endcanany
