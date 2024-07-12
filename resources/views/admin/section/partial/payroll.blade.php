<li
    class="nav-item  {{ request()->routeIs('admin.salary-components.*') ||
    request()->routeIs('admin.payment-methods.*') ||
    //                   request()->routeIs('admin.payment-currency.*') ||
    request()->routeIs('admin.salary-tds.*') ||
    request()->routeIs('admin.salary-salary-groups.*') ||
    request()->routeIs('admin.advance-salaries.*') ||
    request()->routeIs('admin.employee-salaries.*')
        ? 'active'
        : '' }}">
    <a class="nav-link" data-bs-toggle="collapse" href="#payroll" data-href="#" role="button" aria-expanded="false"
        aria-controls="settings">
        <i class="link-icon" data-feather="gift"></i>
        <span class="link-title"> Payroll Management </span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>
    <div class="{{ //                request()->routeIs('admin.payment-currency.*') ||
        request()->routeIs('admin.salary-salary-groups.*') ||
        request()->routeIs('admin.salary-tds.*') ||
        request()->routeIs('admin.advance-salaries.*') ||
        request()->routeIs('admin.employee-salaries.*') ||
        request()->routeIs('admin.employee-salary.payroll*')
            ? ''
            : 'collapse' }} "
        id="payroll">

        <ul class="nav sub-menu">
            <li class="nav-item">
                <a href="{{ route('admin.employee-salary.payroll') }}"
                    data-href="{{ route('admin.employee-salary.payroll') }}"
                    class="nav-link  {{ request()->routeIs('admin.employee-salary.payroll*') ? 'active' : '' }}">Payroll</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.salary-components.index') }}"
                    data-href="{{ route('admin.salary-components.index') }}"
                    class="nav-link {{ request()->routeIs('admin.salary-components.*') ||
                    request()->routeIs('admin.payment-methods.*') ||
                    //                      request()->routeIs('admin.payment-currency.*') ||
                    request()->routeIs('admin.salary-salary-groups.*') ||
                    request()->routeIs('admin.salary-tds.*')
                        ? 'active'
                        : '' }}">Payroll
                    Setting
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.advance-salaries.index') }}"
                    data-href="{{ route('admin.advance-salaries.index') }}"
                    class="nav-link  {{ request()->routeIs('admin.advance-salaries.*') ? 'active' : '' }}">Advance
                    Salary</a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.employee-salaries.index') }}"
                    data-href="{{ route('admin.employee-salaries.index') }}"
                    class="nav-link  {{ request()->routeIs('admin.employee-salaries.*') ? 'active' : '' }}">Employee
                    Salary</a>
            </li>


        </ul>
    </div>
</li>
