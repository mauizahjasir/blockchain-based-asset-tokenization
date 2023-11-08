@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('sidebar')
            <div class="col-md-9">
                <h1>Assets</h1>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Creator</th>
                        <th>Quantity</th>
                        <th>Units</th>
                        <th>Asset Type</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($assets as $asset)
                        <tr>
                            <td>{{ $asset->meta_id }}</td>
                            <td>{{ $asset->name }}</td>
                            <td>{{ $asset->creator_wallet_address }}</td>
                            <td>{{ $asset->quantity }}</td>
                            <td>{{ $asset->unit }}</td>
                            <td>{{ $asset->asset_type_id }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
