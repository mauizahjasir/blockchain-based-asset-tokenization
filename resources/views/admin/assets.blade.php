@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Assets</h1>

                <table class="table table-bordered">
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
                            <td>{{ $asset->creator?->name }}</td>
                            <td>{{ $asset->quantity }}</td>
                            <td>{{ $asset->unit }}</td>
                            <td>{{ $asset->assetType->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
