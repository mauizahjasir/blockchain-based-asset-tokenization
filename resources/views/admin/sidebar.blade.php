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
                    <a href="{{ route('all-users') }}" class="{{ Request::is('multichain/users') ? 'mm-active' : '' }}">
                        All Users
                    </a>
                    <a href="{{ route('new-users') }}" class="{{ Request::is('new-users') ? 'mm-active' : '' }}">
                        New Users Request

                        <span class="badge">{{ \App\Models\User::newUserRequests()->count() }}</span>
                    </a>
                    <a href="{{ route('manage-permissions') }}" class="{{ Request::is('multichain/manage-permissions') ? 'mm-active' : '' }}">
                        Manage Permissions
                    </a>

                </li>

                <li class="app-sidebar__heading">Assets</li>
                <li>
                    <a href="{{ route('create-asset-type') }}" class="{{ Request::is('multichain/assets/create-asset-type') ? 'mm-active' : '' }}">
                        Create Asset Type
                    </a>
                    <a href="{{ route('create-asset') }}" class="{{ Request::is('multichain/assets/create-asset') ? 'mm-active' : '' }}">
                        Create Asset
                    </a>
                </li>

                <li class="app-sidebar__heading">Requests</li>
                <li>
                    <a href="{{ route('asset-requests') }}" class="{{ Request::is('multichain/assets/requests') ? 'mm-active' : '' }}">
                        Pending

                        <span class="badge">{{ \App\Models\AssetsRequest::pendingRequests()->count() }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('asset-requests-history') }}" class="{{ Request::is('multichain/assets/requests/history') ? 'mm-active' : '' }}">
                        Completed
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
