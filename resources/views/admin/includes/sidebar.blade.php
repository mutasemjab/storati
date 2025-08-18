<!-- Main Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="App Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Glovana</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <h4 style="color: white; margin:auto;"> {{ auth()->user()->name }}</h4>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('messages.dashboard') }}</p>
                    </a>
                </li>

                <!-- User Management Section -->
                @canany(['user-table', 'user-add', 'user-edit', 'user-delete'])
                    <li
                        class="nav-item {{ request()->is('admin/users*') || request()->is('admin/drivers*') || request()->is('admin/provider-details*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                {{ __('messages.user_management') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['user-table', 'user-add', 'user-edit', 'user-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>{{ __('messages.users') }}</p>
                                    </a>
                                </li>
                            @endcanany

                        </ul>
                    </li>
                @endcanany

                <!-- Catalog Management (NEW SECTION) -->
                <li
                    class="nav-item {{ request()->is('services*') || request()->is('deliveries*') || request()->is('types*') || request()->is('categories*') || request()->is('products*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            {{ __('messages.Catalog_Management') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('shops.index') }}"
                                class="nav-link {{ request()->routeIs('shops.*') ? 'active' : '' }}">
                                <i class="fas fa-handshake nav-icon"></i>
                                <p>{{ __('messages.Shops') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('celebrities.index') }}"
                                class="nav-link {{ request()->routeIs('celebrities.*') ? 'active' : '' }}">
                                <i class="fas fa-handshake nav-icon"></i>
                                <p>{{ __('messages.Celebrities') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brands.index') }}"
                                class="nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>{{ __('messages.Brands') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}"
                                class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <i class="fas fa-folder nav-icon"></i>
                                <p>{{ __('messages.Categories') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}"
                                class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <i class="fas fa-box nav-icon"></i>
                                <p>{{ __('messages.Products') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('note-vouchers.index') }}"
                                class="nav-link {{ request()->routeIs('note-vouchers.*') ? 'active' : '' }}">
                                <i class="fas fa-box nav-icon"></i>
                                <p>{{ __('messages.note_vouchers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('deliveries.index') }}"
                                class="nav-link {{ request()->routeIs('deliveries.*') ? 'active' : '' }}">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>{{ __('messages.Deliveries') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                 @canany(['order-table', 'order-add', 'order-edit', 'order-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('orders.index') }}"
                                        class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                                        <i class="fas fa-handshake nav-icon"></i>
                                        <p>{{ __('messages.orders') }}</p>
                                    </a>
                                </li>
                 @endcanany

                @canany(['coupon-table', 'coupon-add', 'coupon-edit', 'coupon-delete'])
                    <li class="nav-item">
                        <a href="{{ route('coupons.index') }}"
                            class="nav-link {{ request()->routeIs('coupons.index') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt nav-icon"></i>
                            <p>{{ __('messages.coupons') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('usedCoupons.index') }}"
                            class="nav-link {{ request()->routeIs('usedCoupons.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('messages.coupons_and_users') }}</p>
                        </a>
                    </li>
                @endcanany

                <!-- Notifications -->
                @canany(['notification-table', 'notification-add', 'notification-edit', 'notification-delete'])
                    <li class="nav-item">
                        <a href="{{ route('notifications.create') }}"
                            class="nav-link {{ request()->routeIs('notifications.create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>{{ __('messages.notifications') }}</p>
                        </a>
                    </li>
                @endcanany

                <!-- Content Management -->
                @canany(['page-table', 'page-add', 'page-edit', 'page-delete'])
                    <li class="nav-item">
                        <a href="{{ route('pages.index') }}"
                            class="nav-link {{ request()->routeIs('pages.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('messages.pages') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('banners.index') }}"
                            class="nav-link {{ request()->routeIs('banners.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('messages.banners') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['wallet-table', 'wallet-add', 'wallet-edit', 'wallet-delete'])
                    <li class="nav-item">
                        <a href="{{ route('wallet_transactions.index') }}"
                            class="nav-link {{ request()->routeIs('wallet_transactions.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('messages.Wallet Transactions') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['withdrawal-table', 'withdrawal-add', 'withdrawal-edit', 'withdrawal-delete'])
                    <li class="nav-item">
                        <a href="{{ route('withdrawals.index') }}"
                            class="nav-link {{ request()->routeIs('withdrawals.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('messages.withdrawals') }}</p>
                        </a>
                    </li>
                @endcanany

        





                <!-- System Settings -->
                <li
                    class="nav-item {{ request()->is('admin/settings*') || request()->is('admin/roles*') || request()->is('admin/employees*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            {{ __('messages.system_settings') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('settings.index') }}"
                                class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                                <i class="fas fa-wrench nav-icon"></i>
                                <p>{{ __('messages.general_settings') }}</p>
                            </a>
                        </li>

                        @canany(['role-table', 'role-add', 'role-edit', 'role-delete'])
                            <li class="nav-item">
                                <a href="{{ route('admin.role.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.role.index') ? 'active' : '' }}">
                                    <i class="fas fa-user-shield nav-icon"></i>
                                    <p>{{ __('messages.roles') }}</p>
                                </a>
                            </li>
                        @endcanany

                        @canany(['employee-table', 'employee-add', 'employee-edit', 'employee-delete'])
                            <li class="nav-item">
                                <a href="{{ route('admin.employee.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.employee.index') ? 'active' : '' }}">
                                    <i class="fas fa-user-tie nav-icon"></i>
                                    <p>{{ __('messages.employees') }}</p>
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </li>

                <!-- Account -->
                <li class="nav-item">
                    <a href="{{ route('admin.login.edit', auth()->user()->id) }}"
                        class="nav-link {{ request()->routeIs('admin.login.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>{{ __('messages.admin_account') }}</p>
                    </a>
                </li>

                @canany(['activity-logs-table', 'activity-logs-add', 'activity-logs-edit', 'activity-logs-delete'])
                    <li class="nav-item">
                        <a href="{{ route('admin.activity-logs.index') }}"
                            class="nav-link {{ request()->routeIs('admin.activity-logs.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>{{ __('messages.activity-logs') }}</p>
                        </a>
                    </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>
