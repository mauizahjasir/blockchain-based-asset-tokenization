<div class="col-md-3">
    <div class="list-group">
        <a href="{{ route('home') }}" class="list-group-item list-group-item-action{{ Request::is('home') ? ' active' : '' }}">All Users</a>
        <a href="{{ route('get-information') }}" class="list-group-item list-group-item-action{{ Request::is('get-information') ? ' active' : '' }}">Get Multichain Information</a>
    </div>
</div>
