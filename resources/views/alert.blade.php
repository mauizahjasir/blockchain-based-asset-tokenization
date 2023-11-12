@if (Session::has('success'))
    <div class="alert-container">
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    </div>
@endif

@if (Session::has('errors'))
    <div class="alert-container">
        @foreach(Session::get('errors') as $error)
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endforeach
    </div>
@endif
