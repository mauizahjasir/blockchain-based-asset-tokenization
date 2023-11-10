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

                @if (Session::has('errors'))
                    <div class="alert alert-danger">
                        @foreach(Session::get('errors') as $error)
                            {{ $error . "\n"}}
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('create-asset') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">Asset Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Asset Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="unit">Asset Unit</label>
                        <input type="text" name="unit" id="unit" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="asset_type_id">Asset Type</label>
                        <select name="asset_type_id" id="asset_type_id" class="form-control" required>
                            <option value="">Select an Asset Type</option>
                            @foreach($assetTypes as $assetType)
                                <option value="{{ $assetType->id }}">{{ $assetType->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
