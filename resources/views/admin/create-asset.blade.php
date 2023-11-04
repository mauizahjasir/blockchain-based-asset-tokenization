@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('sidebar')
            <div class="col-md-9">
                <h1>Create Asset</h1>

                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('create-asset') }}">
                    @csrf

                    <div class="form-group">
                        <label for="asset_name">Asset Name</label>
                        <input type="text" name="asset_name" id="asset_name" class="form-control" required>
                    </div>

                    <br/>
                    <div class="form-group">
                        <label for="asset_quantity">Asset Quantity</label>
                        <input type="number" name="asset_quantity" id="asset_quantity" class="form-control" required>
                    </div>

                    <br/>
                    <div class="form-group">
                        <label for="asset_unit">Asset Unit</label>
                        <input type="text" name="asset_unit" id="asset_unit" class="form-control" required>
                    </div>

                    <br/>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
