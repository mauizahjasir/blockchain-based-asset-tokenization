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
                        Dashboard
                    </a>
                </li>

                <li class="app-sidebar__heading">My Assets</li>
                <li>
                    <a href="{{ route('client-assets') }}"
                       class="{{ Request::is('client/my-assets') ? 'mm-active' : '' }}">
                        Sell
                    </a>

                    <a href="{{ route('assets-on-sale') }}"
                       class="{{ Request::is('assets/assets-on-sale') ? 'mm-active' : '' }}">
                        Buy
                    </a>
                </li>

                <li class="app-sidebar__heading">Bank Assets</li>
                <li>
                    <a href="{{ route('bank-assets') }}"
                       class="{{ Request::is('client/bank-assets') ? 'mm-active' : '' }}">
                        Buy
                    </a>
                </li>

                <li class="app-sidebar__heading">My Requests</li>

                <li>
                    Incoming
                </li>
                <ul>
                    <li>
                        <a href="{{ route('incoming-requests') }}"
                           class="{{ Request::is('assets/request/incoming-requests') ? 'mm-active' : '' }}">
                            In Process
                            <span class="badge">{{ \App\Models\AssetsRequest::incomingRequests()->count() }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('incoming-requests-history') }}"
                           class="{{ Request::is('assets/request/incoming-requests/history') ? 'mm-active' : '' }}">
                            Completed
                        </a>
                    </li>
                </ul>

                <li>
                    Outgoing
                </li>
                <ul>
                    <li>
                        <a href="{{ route('outgoing-requests') }}"
                           class="{{ Request::is('assets/request/outgoing-requests') ? 'mm-active' : '' }}">
                            In Process
                            <span class="badge">{{ \App\Models\AssetsRequest::outgoingRequests()->count() }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('outgoing-requests-history') }}"
                           class="{{ Request::is('assets/request/outgoing-requests/history') ? 'mm-active' : '' }}">
                            Completed
                        </a>
                    </li>
                </ul>
            </ul>
        </div>
    </div>
</div>
