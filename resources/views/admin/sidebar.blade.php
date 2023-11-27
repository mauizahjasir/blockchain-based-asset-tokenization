<style>
    .badge {
        background-color: #3f6ad8; /* Background color for the badge */
        color: white; /* Text color for the badge */
        padding: 6px; /* Adjust padding as needed */
        border-radius: 50%; /* Make it a circle */
        margin-left: 5px; /* Adjust margin as needed */
    }
</style>

<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                        data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
                        <span>
                            <button type="button"
                                    class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                <span class="btn-icon-wrapper">
                                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                                </span>
                            </button>
                        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Home</li>
                <li>
                    <a href="{{ route('home') }}" class="{{ Request::is('home') ? 'mm-active' : '' }}">
                        Multichain Dashboard
                    </a>
                </li>
                <li class="app-sidebar__heading">Users</li>
                <li>
                    <a href="{{ route('all-users') }}" class="{{ Request::is('admin/users') ? 'mm-active' : '' }}">
                        All Users
                    </a>
                    <a href="{{ route('new-users') }}" class="{{ Request::is('admin/new-users') ? 'mm-active' : '' }}">
                        New Users Request

                        <span class="badge">{{ \App\Models\User::newUserRequests()->count() }}</span>
                    </a>
                    <a href="{{ route('users.manage-permissions') }}"
                       class="{{ Request::is('users/admin/manage-permissions') ? 'mm-active' : '' }}">
                        Manage Permissions
                    </a>

                </li>

                <li class="app-sidebar__heading">Assets</li>
                <li>
                    <a href="{{ route('admin.assets') }}"
                       class="{{ Request::is('admin/my-assets') ? 'mm-active' : '' }}">
                        My Assets
                    </a>
                    <a href="{{ route('asset-type.create') }}"
                       class="{{ Request::is('admin/assetType/create') ? 'mm-active' : '' }}">
                        Create Asset Type
                    </a>
                    <a href="{{ route('assets.create') }}"
                       class="{{ Request::is('admin/assets/create') ? 'mm-active' : '' }}">
                        Create Asset
                    </a>

                    <a href="{{ route('admin.requests') }}"
                       class="{{ Request::is('admin/assets/requests') ? 'mm-active' : '' }}">
                        Requests

                        <span class="badge">{{ \App\Models\AssetsRequest::pendingRequests()->count() }}</span>
                    </a>
                </li>

                <li class="app-sidebar__heading">Transactions</li>
                <li>
                    <a href="{{ route('assets.transactions') }}"
                       class="{{ Request::is('admin/assets/transactions') ? 'mm-active' : '' }}">
                        Pending

                        <span class="badge">{{ \App\Models\AssetsRequest::pendingTransactions()->count() }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('transactions.history') }}"
                       class="{{ Request::is('admin/assets/transactions/history') ? 'mm-active' : '' }}">
                        Completed
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
