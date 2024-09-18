@canany(['manage-roles', 'manage-general_settings'])
    <li
        class="nav-item  {{ request()->routeIs('admin.roles.*') ||
        request()->routeIs('admin.notifications.*') ||
        request()->routeIs('admin.general-settings.*') ||
        request()->routeIs('admin.payment-currency.*') ||
        request()->routeIs('admin.app-settings.*') ||
        request()->routeIs('admin.feature.*') ||
        request()->routeIs('admin.modules.*') ||
        request()->routeIs('admin.static-page-contents.*') ||
        request()->routeIs('admin.banks.*') ||
        request()->routeIs('admin.bank_branches.*')
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
        request()->routeIs('admin.feature.*') ||
        request()->routeIs('admin.modules.*') ||
        request()->routeIs('admin.static-page-contents.*') ||
        request()->routeIs('admin.banks.*') ||
        request()->routeIs('admin.bank_branches.*')
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
                @can('manage-modules')
                    <li class="nav-item">
                        <a href="{{ route('admin.modules.index') }}" data-href="{{ route('admin.modules.index') }}"
                            class="nav-link {{ request()->routeIs('admin.modules.*') ? 'active' : '' }}">
                            <span>Modules</span>
                        </a>
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
                @canany('manage-bank', 'manage-bank_branch')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'banks' || Request::segment(2) == 'bank_branches' ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#bank" data-href="#" role="button" aria-expanded="false">
                        <span> {{ __('Bank') }} </span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="{{ Request::segment(2) == 'banks' || Request::segment(2) == 'bank_branches' ? '' : 'collapse' }}"
                        id="bank">
                        <ul class="nav sub-menu">
                            @can('manage-bank')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.banks.index' || Request::route()->getName() == 'admin.banks.create' || Request::route()->getName() == 'admin.banks.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.banks.index') }}">{{ __('Banks') }}</a>
                            </li>
                            @endcan
                            @can('manage-bank_branch')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.bank_branches.index' || Request::route()->getName() == 'admin.bank_branches.create' || Request::route()->getName() == 'admin.bank_branches.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.bank_branches.index') }}">{{ __('Branches') }}</a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'banks' || Request::segment(2) == 'bank_branches' ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#location" data-href="#" role="button" aria-expanded="false">
                        <span> {{ __('Location') }} </span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="{{ Request::segment(2) == 'banks' || Request::segment(2) == 'bank_branches' ? '' : 'collapse' }}"
                        id="location">
                        <ul class="nav sub-menu">
                            {{-- @can('manage-bank') --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.country.index' || Request::route()->getName() == 'admin.banks.create' || Request::route()->getName() == 'admin.banks.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.banks.index') }}">{{ __('Country') }}</a>
                            </li>
                            {{-- @endcan --}}
                            {{-- @can('manage-bank_branch') --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.bank_branches.index' || Request::route()->getName() == 'admin.bank_branches.create' || Request::route()->getName() == 'admin.bank_branches.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.bank_branches.index') }}">{{ __('State') }}</a>
                            </li>
                            {{-- @endcan --}}
                            {{-- @can('manage-bank_branch') --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.bank_branches.index' || Request::route()->getName() == 'admin.bank_branches.create' || Request::route()->getName() == 'admin.bank_branches.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.bank_branches.index') }}">{{ __('District') }}</a>
                            </li>
                            {{-- @endcan --}}
                            {{-- @can('manage-bank_branch') --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.bank_branches.index' || Request::route()->getName() == 'admin.bank_branches.create' || Request::route()->getName() == 'admin.bank_branches.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.bank_branches.index') }}">{{ __('Block') }}</a>
                            </li>
                            {{-- @endcan --}}
                            {{-- @can('manage-bank_branch') --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.bank_branches.index' || Request::route()->getName() == 'admin.bank_branches.create' || Request::route()->getName() == 'admin.bank_branches.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.bank_branches.index') }}">{{ __('Gram Panchayat') }}</a>
                            </li>
                            {{-- @endcan --}}
                            {{-- @can('manage-bank_branch') --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.bank_branches.index' || Request::route()->getName() == 'admin.bank_branches.create' || Request::route()->getName() == 'admin.bank_branches.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.bank_branches.index') }}">{{ __('Village') }}</a>
                            </li>
                            {{-- @endcan --}}
                            {{-- @can('manage-bank_branch') --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.bank_branches.index' || Request::route()->getName() == 'admin.bank_branches.create' || Request::route()->getName() == 'admin.bank_branches.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.bank_branches.index') }}">{{ __('Zone') }}</a>
                            </li>
                            {{-- @endcan --}}
                            {{-- @can('manage-bank_branch') --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::route()->getName() == 'admin.bank_branches.index' || Request::route()->getName() == 'admin.bank_branches.create' || Request::route()->getName() == 'admin.bank_branches.edit' ? ' active' : '' }}"
                                    href="{{ route('admin.bank_branches.index') }}">{{ __('Center') }}</a>
                            </li>
                            {{-- @endcan --}}
                        </ul>
                    </div>
                </li>
                @can('manage-content_management')
                    <li class="nav-item">
                        <a href="{{ route('admin.static-page-contents.index') }}"
                            data-href="{{ route('admin.static-page-contents.index') }}"
                            class="nav-link {{ request()->routeIs('admin.static-page-contents.*') ? 'active' : '' }}">
                            Content Management
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </li>
@endcanany
