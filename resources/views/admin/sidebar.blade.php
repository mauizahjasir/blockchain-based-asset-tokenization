{{--
<div class="w3-bar-block">
    <a href="{{ route('home') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('home') ? ' active' : '' }}">All Users</a>
    <a href="{{ route('new-users') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('new-users') ? ' active' : '' }}">New Users Request</a>
    <a href="{{ route('get-information') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('get-information') ? ' active' : '' }}">Get Multichain Information</a>
    <a href="{{ route('manage-permissions') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('manage-permissions') ? ' active' : '' }}">Manage Permissions</a>
    <a href="{{ route('create-asset-type') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('create-asset-type') ? ' active' : '' }}">Create Asset Type</a>
    <a href="{{ route('my-assets') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('my-assets') ? ' active' : '' }}">My Assets</a>
    <a href="{{ route('all-assets') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('all-assets') ? ' active' : '' }}">All Assets</a>
    <a href="{{ route('create-asset') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('create-asset') ? ' active' : '' }}">Create Asset</a>
    <a href="{{ route('asset-requests') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('asset-requests') ? ' active' : '' }}">Assets Request</a>
</div>
--}}

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

                        <span class="badge">{{ \App\Models\User::whereNull('wallet_address')->get()->count() }}</span>
                    </a>
                    <a href="{{ route('manage-permissions') }}" class="{{ Request::is('manage-permissions') ? 'mm-active' : '' }}">
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
            </ul>
        </div>
    </div>
</div>
