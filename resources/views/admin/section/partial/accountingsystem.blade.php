<li
    class="nav-item {{ Request::route()->getName() == 'print-setting' ||
    Request::segment(1) == 'customer' ||
    Request::segment(1) == 'vender' ||
    Request::segment(1) == 'proposal' ||
    Request::segment(1) == 'bank-account' ||
    Request::segment(1) == 'bank-transfer' ||
    Request::segment(1) == 'invoice' ||
    Request::segment(1) == 'revenue' ||
    Request::segment(1) == 'credit-note' ||
    Request::segment(1) == 'taxes' ||
    Request::segment(1) == 'product-category' ||
    Request::segment(1) == 'product-unit' ||
    Request::segment(1) == 'payment-method' ||
    Request::segment(1) == 'custom-field' ||
    Request::segment(1) == 'chart-of-account-type' ||
    (Request::segment(1) == 'transaction' &&
        Request::segment(2) != 'ledger' &&
        Request::segment(2) != 'balance-sheet' &&
        Request::segment(2) != 'trial-balance') ||
    Request::segment(1) == 'goal' ||
    Request::segment(1) == 'budget' ||
    Request::segment(1) == 'chart-of-account' ||
    Request::segment(1) == 'journal-entry' ||
    Request::segment(2) == 'ledger' ||
    Request::segment(2) == 'balance-sheet' ||
    Request::segment(2) == 'trial-balance' ||
    Request::segment(1) == 'bill' ||
    Request::segment(1) == 'expense' ||
    Request::segment(1) == 'payment' ||
    Request::segment(1) == 'debit-note'
        ? ' active'
        : '' }}">
    <a class="nav-link" data-bs-toggle="collapse" href="#account" data-href="#" role="button" aria-expanded="false"
        aria-controls="settings">
        <i class="link-icon" data-feather="settings"></i>
        <span class="link-title"> {{ __('Accounting System ') }} </span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>
    <div class="{{ request()->routeIs('admin.roles.*') ||
    request()->routeIs('admin.notifications.*') ||
    request()->routeIs('admin.general-settings.*') ||
    request()->routeIs('admin.payment-currency.*') ||
    request()->routeIs('admin.app-settings.*') ||
    request()->routeIs('admin.feature.*') ||
    request()->routeIs('admin.modules.*') ||
    request()->routeIs('admin.static-page-contents.*')
        ? ''
        : 'collapse' }} "
        id="account">
        <ul class="nav sub-menu">

            @if (Gate::check('manage bank account') || Gate::check('manage bank transfer'))
                <li
                    class="nav-item {{ Request::segment(1) == 'bank-account' || Request::segment(1) == 'bank-transfer' ? 'active' : '' }}">

                    <a class="nav-link" data-bs-toggle="collapse" href="#banking" data-href="#" role="button"
                        aria-expanded="false">
                        <span> {{ __('Banking') }} </span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse" id="banking">
                        <ul class="nav sub-menu">
                            <li
                                class="nav-item {{ Request::route()->getName() == 'admin.bank-account.index' || Request::route()->getName() == 'admin.bank-account.create' || Request::route()->getName() == 'admin.bank-account.edit' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.bank-account.index') }}">{{ __('Account') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'admin.bank-transfer.index' || Request::route()->getName() == 'admin.bank-transfer.create' || Request::route()->getName() == 'admin.bank-transfer.edit' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.bank-transfer.index') }}">{{ __('Transfer') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if (Gate::check('manage invoice') || Gate::check('manage revenue') || Gate::check('manage credit note'))
                <li
                    class="nav-item {{ Request::segment(1) == 'customer' || Request::segment(1) == 'proposal' || Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note' ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sales" data-href="#" role="button"
                        aria-expanded="false"><span>{{ __('Sales') }}</span><i class="link-arrow"
                            data-feather="chevron-down"></i></a>
                    <div class="collapse" id="sales">
                        <ul class="nav sub-menu">
                            @if (Gate::check('manage customer'))
                                <li class="nav-item {{ Request::segment(1) == 'customer' ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('admin.customer.index') }}">{{ __('Customer') }}</a>
                                </li>
                            @endif
                            @if (Gate::check('manage proposal'))
                                <li class="nav-item {{ Request::segment(1) == 'proposal' ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('admin.proposal.index') }}">{{ __('Estimate') }}</a>
                                </li>
                            @endif
                            <li
                                class="nav-item {{ Request::route()->getName() == 'admin.invoice.index' || Request::route()->getName() == 'admin.invoice.create' || Request::route()->getName() == 'admin.invoice.edit' || Request::route()->getName() == 'admin.invoice.show' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.invoice.index') }}">{{ __('Invoice') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'admin.revenue.index' || Request::route()->getName() == 'admin.revenue.create' || Request::route()->getName() == 'admin.revenue.edit' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.revenue.index') }}">{{ __('Revenue') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'admin.credit.note' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.credit.note') }}">{{ __('Credit Note') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if (Gate::check('manage bill') || Gate::check('manage payment') || Gate::check('manage debit note'))
                <li
                    class="nav-item {{ Request::segment(1) == 'bill' || Request::segment(1) == 'vender' || Request::segment(1) == 'expense' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note' ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#purchase" data-href="#" role="button"
                        aria-expanded="false"><span>{{ __('Purchases') }}</span><i class="link-arrow"
                            data-feather="chevron-down"></i></a>
                    <div class="collapse" id="purchase">
                        <ul class="nav sub-menu">
                            @if (Gate::check('manage vender'))
                                <li class="nav-item {{ Request::segment(1) == 'vender' ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('admin.vender.index') }}">{{ __('Suppiler') }}</a>
                                </li>
                            @endif
                            <li
                                class="nav-item {{ Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.bill.index') }}">{{ __('Bill') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'expense.index' || Request::route()->getName() == 'expense.create' || Request::route()->getName() == 'expense.edit' || Request::route()->getName() == 'expense.show' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.expense.index') }}">{{ __('Expense') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'payment.index' || Request::route()->getName() == 'payment.create' || Request::route()->getName() == 'payment.edit' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.payment.index') }}">{{ __('Payment') }}</a>
                            </li>
                            <li class="nav-item  {{ Request::route()->getName() == 'debit.note' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.debit.note') }}">{{ __('Debit Note') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if (Gate::check('manage chart of account') ||
                    Gate::check('manage journal entry') ||
                    Gate::check('balance sheet report') ||
                    Gate::check('ledger report') ||
                    Gate::check('trial balance report'))
                <li
                    class="nav-item {{ Request::segment(1) == 'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' || Request::segment(2) == 'balance-sheet' || Request::segment(2) == 'trial-balance' ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#doubleentry" data-href="#" role="button"
                        aria-expanded="false"><span>{{ __('Double Entry') }}</span><i class="link-arrow"
                            data-feather="chevron-down"></i></a>
                    <div class="collapse" id="doubleentry">
                        <ul class="nav sub-menu">
                            <li
                                class="nav-item {{ Request::route()->getName() == 'chart-of-account.index' || Request::route()->getName() == 'chart-of-account.show' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.chart-of-account.index') }}">{{ __('Chart of Accounts') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'journal-entry.edit' || Request::route()->getName() == 'journal-entry.create' || Request::route()->getName() == 'journal-entry.index' || Request::route()->getName() == 'journal-entry.show' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.journal-entry.index') }}">{{ __('Journal Account') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'report.ledger' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.report.ledger') }}">{{ __('Ledger Summary') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'report.balance.sheet' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.report.balance.sheet') }}">{{ __('Balance Sheet') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'report.profit.loss' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.report.profit.loss') }}">{{ __('Profit & Loss') }}</a>
                            </li>
                            <li
                                class="nav-item {{ Request::route()->getName() == 'trial.balance' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.trial.balance') }}">{{ __('Trial Balance') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if (\Auth::user()->type == 'company')
                <li class="nav-item {{ Request::segment(1) == 'budget' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.budget.index') }}">{{ __('Budget Planner') }}</a>
                </li>
            @endif
            @if (Gate::check('manage goal'))
                <li class="nav-item {{ Request::segment(1) == 'goal' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.goal.index') }}">{{ __('Financial Goal') }}</a>
                </li>
            @endif
            @if (Gate::check('manage constant tax') ||
                    Gate::check('manage constant category') ||
                    Gate::check('manage constant unit') ||
                    Gate::check('manage constant payment method') ||
                    Gate::check('manage constant custom field'))
                <li
                    class="nav-item {{ Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.taxes.index') }}">{{ __('Accounting Setup') }}</a>
                </li>
            @endif

            @if (Gate::check('manage print settings'))
                <li class="nav-item {{ Request::route()->getName() == 'print-setting' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.print.setting') }}">{{ __('Print Settings') }}</a>
                </li>
            @endif
        </ul>
    </div>
</li>
