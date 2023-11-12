<div class="w3-bar-block">
    <a href="{{ route('home') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('home') ? ' active' : '' }}">Home</a>
    <a href="{{ route('client.assets') }}" class="w3-bar-item w3-button w3-padding w3-text-white{{ Request::is('assets') ? ' active' : '' }}">Assets</a>
</div>
