<div class="w3-bar-block">
    <a href="{{ route('home') }}" class="w3-bar-item w3-button w3-padding w3-text-teal{{ Request::is('home') ? ' active' : '' }}"><div style="color:white;">All Users</div></a>
    <a href="{{ route('new-users') }}" class="w3-bar-item w3-button w3-padding w3-text-teal{{ Request::is('new-users') ? ' active' : '' }}"><div style="color:white;">New Users Request</div></a>
    <a href="{{ route('get-information') }}" class="w3-bar-item w3-button w3-padding w3-text-teal{{ Request::is('get-information') ? ' active' : '' }}"><div style="color:white;">Get Multichain Information</div></a>
    <a href="{{ route('create-asset-type') }}" class="w3-bar-item w3-button w3-padding w3-text-teal{{ Request::is('create-asset-type') ? ' active' : '' }}"><div style="color:white;">Create Asset Type</div></a>
    <a href="{{ route('assets') }}" class="w3-bar-item w3-button w3-padding w3-text-teal{{ Request::is('assets') ? ' active' : '' }}"><div style="color:white;">Assets</div></a>
    <a href="{{ route('create-asset') }}" class="w3-bar-item w3-button w3-padding w3-text-teal{{ Request::is('create-asset') ? ' active' : '' }}"><div style="color:white;">Create Asset</div></a>
</div>
