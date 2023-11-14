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
                                    <th>Requested Asset ID</th>
                                    <th>Requested Asset Name</th>
                                    <th>Reqestor Name</th>
                                    <th>Requestor Wallet Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    </thead>
                                    <tbody>

                                    @foreach ($assetsRequest as $request)
                                        <tr>
                                            <td>{{ $request->assets?->meta_id }}</td>
                                            <td>{{ $request->assets?->name }}</td>
                                            <td>{{ $request->requestor?->name }}</td>
                                            <td>{{ $request->requestor?->wallet_address }}</td>
                                            <td>{{ $request->status }}</td>
                                            <td>
                                                <form method="GET" action="{{ route('request-details', ['assetRequest' => $request->meta_id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="request_id" value="{{ $request->id }}">
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
