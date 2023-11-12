@extends('layouts.client.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">

        @if (Session::has('success'))
            <div class="w3-panel w3-green">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        @if (Session::has('errors'))
            <div class="w3-panel w3-red">
                @foreach(Session::get('errors') as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Assets</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Creator</th>
                                    <th>Quantity</th>
                                    <th>Units</th>
                                    <th>Asset Type</th>
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
                </div>
            </div>
        </div>
    </div>
@endsection
