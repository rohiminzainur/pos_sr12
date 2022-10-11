<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->photo ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">

            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
                <li class="header">Master</li>
                <li>
                    <a href="{{ route('categories.index') }}">
                        <i class="fa fa-th-large"></i> <span>Categories</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}">
                        <i class="fa fa-cubes"></i> <span>Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}">
                        <i class="fa fa-truck"></i> <span>Suppliers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('members.index') }}">
                        <i class="fa fa-users"></i> <span>Members</span>
                    </a>
                </li>
                <li class="header">Transactions</li>
                <li>
                    <a href="{{ route('expenses.index') }}">
                        <i class="fa fa-money"></i> <span>Expenses</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchases.index') }}">
                        <i class="fa fa-download"></i> <span>Purchases</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sales.index') }}">
                        <i class="fa fa-upload"></i> <span>Sales</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transactions.index') }}">
                        <i class="fa fa-cart-arrow-down"></i> <span>Transactions Aktif</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transactions.new') }}">
                        <i class="fa fa-cart-plus"></i> <span>Transactions New</span>
                    </a>
                </li>
                <li class="header">Report</li>
                <li>
                    <a href="{{ route('report.index') }}">
                        <i class="fa fa-file-pdf-o"></i> <span>Reports</span>
                    </a>
                </li>
                <li class="header">System</li>
                <li>
                    <a href="{{ route('users.index') }}">
                        <i class="fa fa-user-circle"></i> <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.index') }}">
                        <i class="fa fa-cogs"></i> <span>Settings</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('transactions.index') }}">
                        <i class="fa fa-cart-arrow-down"></i> <span>Transactions Aktif</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transactions.new') }}">
                        <i class="fa fa-cart-plus"></i> <span>Transactions New</span>
                    </a>
                </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
