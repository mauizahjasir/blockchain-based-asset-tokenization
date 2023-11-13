@extends('layouts.client.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">

        @include('alert');

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
                                    <th>Action</th> <!-- Add this column for the action button -->
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
                                            <td>
                                                <form method="GET" action="{{ route('request-purchase', ['asset' => $asset->meta_id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                                    <button type="submit" class="btn btn-primary" style="height: 30px; font-size: 12px">Submit Buy Request</button>
                                                </form>
                                            </td>
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
