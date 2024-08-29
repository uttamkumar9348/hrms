@canany(['manage-routers', 'manage-nfc', 'manage-qr'])
    <li
        class="nav-item  {{ request()->routeIs('admin.routers.*') ||
        request()->routeIs('admin.qr.*') ||
        request()->routeIs('admin.nfc.*')
            ? 'active'
            : '' }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#attendance_method" data-href="#" role="button"
            aria-expanded="false" aria-controls="settings">
            <i class="link-icon" data-feather="tool"></i>
            <span class="link-title"> Attendance Methods </span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="{{ request()->routeIs('admin.routers.*') ||
        request()->routeIs('admin.qr.*') ||
        request()->routeIs('admin.nfc.*')
            ? ''
            : 'collapse' }} "
            id="attendance_method">

            <ul class="nav sub-menu">

                @can('manage-routers')
                    <li class="nav-item">
                        <a href="{{ route('admin.routers.index') }}" data-href="{{ route('admin.routers.index') }}"
                            class="nav-link {{ request()->routeIs('admin.routers.*') ? 'active' : '' }}">Routers
                        </a>
                    </li>
                @endcan

                @can('manage-nfc')
                    <li class="nav-item">
                        <a href="{{ route('admin.nfc.index') }}" data-href="{{ route('admin.nfc.index') }}"
                            class="nav-link {{ request()->routeIs('admin.nfc.*') ? 'active' : '' }}">NFC</a>
                    </li>
                @endcan

                @can('manage-qr')
                    <li class="nav-item">
                        <a href="{{ route('admin.qr.index') }}" data-href="{{ route('admin.qr.index') }}"
                            class="nav-link {{ request()->routeIs('admin.qr.*') ? 'active' : '' }}">QR</a>
                    </li>
                @endcan
            </ul>
        </div>
    </li>
@endcanany


@canany(['manage-roles', 'manage-general_settings'])
    <li
        class="nav-item  {{ request()->routeIs('admin.roles.*') ||
        request()->routeIs('admin.notifications.*') ||
        request()->routeIs('admin.general-settings.*') ||
        request()->routeIs('admin.payment-currency.*') ||
        request()->routeIs('admin.app-settings.*') ||
        request()->routeIs('admin.feature.*')
            ? 'active'
            : '' }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#setting" data-href="#" role="button" aria-expanded="false"
            aria-controls="settings">
            <i class="link-icon" data-feather="settings"></i>
            <span class="link-title"> Setting </span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="{{ request()->routeIs('admin.roles.*') ||
        request()->routeIs('admin.notifications.*') ||
        request()->routeIs('admin.general-settings.*') ||
        request()->routeIs('admin.payment-currency.*') ||
        request()->routeIs('admin.app-settings.*') ||
        request()->routeIs('admin.feature.*')
            ? ''
            : 'collapse' }} "
            id="setting">

            <ul class="nav sub-menu">
                @can('manage-roles')
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}" data-href="{{ route('admin.roles.index') }}"
                            class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">Roles & Permissions</a>
                    </li>
                @endcan

                @can('manage-general_settings')
                    <li class="nav-item">
                        <a href="{{ route('admin.general-settings.index') }}"
                            data-href="{{ route('admin.general-settings.index') }}"
                            class="nav-link {{ request()->routeIs('admin.general-settings.*') ? 'active' : '' }}">General
                            Settings</a>
                    </li>
                @endcan

                <li class="nav-item">
                    <a href="{{ route('admin.app-settings.index') }}" data-href="{{ route('admin.app-settings.index') }}"
                        class="nav-link {{ request()->routeIs('admin.app-settings.*') ? 'active' : '' }}">App Settings</a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.notifications.index') }}"
                        data-href="{{ route('admin.notifications.index') }}"
                        class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">Notifications</a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.payment-currency.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payment-currency.index') }}"
                        data-href="{{ route('admin.payment-currency.index') }}"
                        class="nav-link {{ request()->routeIs('admin.payment-currency.*') ? 'active' : '' }}"> Payment
                        Currency</a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.feature.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.feature.index') }}" data-href="{{ route('admin.feature.index') }}"
                        class="nav-link {{ request()->routeIs('admin.feature.index') ? 'active' : '' }}"> Feature
                        Control</a>
                </li>
            </ul>
        </div>
    </li>
@endcanany
