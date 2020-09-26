<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">Project</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route("admin.home") }}" class="nav-link">

                            <i class="fa fa-dashboard">

                            </i>
                        <p>
                            <span>{{ trans('global.dashboard') }}</span>
                        </p>
                    </a>
                </li>
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is('admin/permissions*') ? 'menu-open' : '' }} {{ request()->is('admin/roles*') ? 'menu-open' : '' }} {{ request()->is('admin/users*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle">
                            <i class="fa fa-users">

                            </i>
                            <p>
                                <span>{{ trans('global.userManagement.title') }}</span>
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                        <i class="fa fa-unlock-alt">

                                        </i>
                                        <p>
                                            <span>{{ trans('global.permission.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                        <i class="fa fa-briefcase">

                                        </i>
                                        <p>
                                            <span>{{ trans('global.role.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                        <i class="fa fa-user">

                                        </i>
                                        <p>
                                            <span>{{ trans('global.user.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('product_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.yadiProducts.index") }}" class="nav-link {{ request()->is('admin/yadiProducts') || request()->is('admin/yadiProducts/*') ? 'active' : '' }}">
                            <i class="fa fa-anchor">

                            </i>
                            <p>
                                <span>Customized(Yadi) Products</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('product_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.products.index") }}" class="nav-link {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'active' : '' }}">
                            <i class="fa fa-anchor">

                            </i>
                            <p>
                                <span>{{ trans('global.product.title') }}</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('product_category_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.productsCategory.index") }}" class="nav-link {{ request()->is('admin/productsCategory') || request()->is('admin/productsCategory/*') ? 'active' : '' }}">
                            <i class="fa fa-eercast">

                            </i>
                            <p>
                                <span>Product Category</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('product_subcategory_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.productsSubCategory.index") }}" class="nav-link {{ request()->is('admin/productsSubCategory') || request()->is('admin/productsSubCategory/*') ? 'active' : '' }}">
                            <i class="fa fa-eercast">

                            </i>
                            <p>
                                <span>Product Sub Category</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('view_banner')
                    <li class="nav-item">
                        <a href="{{ route("admin.banners.index") }}" class="nav-link {{ request()->is('admin/banners') || request()->is('admin/banners/*') ? 'active' : '' }}">
                            <i class="fa fa-eercast">

                            </i>
                            <p>
                                <span>App Banner</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('view_order')
                    <li class="nav-item">
                        <a href="{{ route("admin.orders.index") }}" class="nav-link {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'active' : '' }}">
                            <i class="fa fa-eercast">

                            </i>
                            <p>
                                <span>All Orders</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('view_tickets')
                    <li class="nav-item">
                        <a href="{{ route("admin.tickets.index") }}" class="nav-link {{ request()->is('admin/tickets') || request()->is('admin/tickets/*') ? 'active' : '' }}">
                            <i class="fa fa-eercast">

                            </i>
                            <p>
                                <span>Support Tickets</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('access_coupon')
                    <li class="nav-item">
                        <a href="{{ route("admin.coupons.index") }}" class="nav-link {{ request()->is('admin/coupons') || request()->is('admin/coupons/*') ? 'active' : '' }}">
                            <i class="fa fa-eercast">

                            </i>
                            <p>
                                <span>Discount Coupons</span>
                            </p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">

                            <i class="fa fa-sign-out">

                            </i>
                        <p>
                            <span>{{ trans('global.logout') }}</span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
