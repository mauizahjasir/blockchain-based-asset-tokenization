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
