@extends('layouts.admin.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">
        @include('alert')

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Assets Requests Details</h4>
                        </div>
                        <div class="card-body">
                            <div>
                                <div class="mb-3">Please review the following details and choose to accept or decline the request:</div>

                                <div><b>Requestor name:</b> {{ $assetRequest->requestor->name }}</div>
                                <div><b>Requestor wallet address:</b> {{ $assetRequest->requestor->wallet_address }}</div>
                                <div><b>Requestor description:</b> {{ $assetRequest->additional_info }}</div>
                                <div><b>Amount committed by requestor:</b> {{ $assetRequest->commit_amount }}</div>
                            </div>
                            <br/>
                            <div class="row mt-2 ml-1">

                                <form action="{{ route('request-approve', $assetRequest->meta_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success mr-2" {{ $assetRequest->status !== \App\Models\AssetsRequest::OPEN ? 'disabled' : '' }}>Approve</button>
                                </form>

                                <form action="{{ route('request-reject', $assetRequest->meta_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger mr-2" {{ $assetRequest->status !== \App\Models\AssetsRequest::OPEN ? 'disabled' : '' }}>Reject</button>
                                </form>

                                <a href="{{ route('asset-requests') }}" class="btn btn-secondary mr-2">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
