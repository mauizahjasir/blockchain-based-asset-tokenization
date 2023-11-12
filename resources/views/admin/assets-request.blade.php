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
                                    </thead>
                                    <tbody>

                                    @foreach ($assetsRequest as $request)
                                        <tr>
                                            <td>{{ $request->assets?->meta_id }}</td>
                                            <td>{{ $request->assets?->name }}</td>
                                            <td>{{ $request->requestor?->name }}</td>
                                            <td>{{ $request->requestor?->wallet_address }}</td>
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
