@extends('layouts.admin.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">

        @include('alert')

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Assets Requests</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                    <th>Requested Asset Name</th>
                                    <th>Reqestor Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    </thead>
                                    <tbody>

                                    @foreach ($assetsRequests as $assetsRequest)
                                        <tr>
                                            <td>{{ $assetsRequest->asset }}</td>
                                            <td>{{ $assetsRequest->requestor?->name }}</td>
                                            <td>{{ $assetsRequest->status }}</td>
                                            <td>
                                                <form method="GET" action="{{ route('request-details', ['assetRequest' => $assetsRequest->meta_id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="request_id" value="{{ $assetsRequest->id }}">
                                                    <button type="submit" class="btn btn-primary" style="height: 30px; font-size: 12px">View Details</button>
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
