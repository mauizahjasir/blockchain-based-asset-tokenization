@if (Session::has('success'))
    <div class="alert-container">
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    </div>
@endif

@if (Session::has('errors'))
    <div class="alert-container">
        @if(is_array(Session::get('errors')))
            @foreach(Session::get('errors') as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach
        @else
            <div class="alert alert-danger">
                {{ Session::get('errors') }}
            </div>
        @endif
    </div>
@endif

@if (Session::has('data'))
    <div class="alert-container">
        <div class="alert alert-success">
            {{ Session::get('data') }}
        </div>
    </div>
@endif
