@extends('layouts.app')

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    @include('admin.topbar')
    <div class="app-main">
        @include('admin.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                @include('alert')
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-header">Request Details</div>
                            <div class="stamp-card">
                                <div class="ml-2 mt-2">
                                    <div class="mb-3"><b>Please review the following details and choose to accept or
                                            decline
                                            the request:</b></div>

                                    <div><b>Requestor's name:</b> {{ $assetRequest->requestor->name }}</div>
                                    <div><b>Owner's name:</b> {{ $assetRequest->owner->name }}</div>
                                    <div><b>Requestor's wallet
                                            address:</b> {{ $assetRequest->requestor->wallet_address }}
                                    </div>
                                    <div><b>Owner's wallet address:</b> {{ $assetRequest->owner->wallet_address }}</div>
                                    <div><b>Amount committed by requestor:</b> {{ $assetRequest->commit_amount }}</div>
                                    <div><b>Has the owner transferred asset to my wallet? </b>
                                        @if ($assetTransferred)
                                            <div class="badge badge-success">Successfully transferred</div>
                                        @else
                                            <div class="badge badge-danger">Not transferred</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 ml-4">

                                <form action="{{ route('request-approve', $assetRequest->meta_id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-success mr-2" {{ !$assetTransferred || in_array($assetRequest->status, [\App\Models\AssetsRequest::RESOLVED. \App\Models\AssetsRequest::REJECTED, \App\Models\AssetsRequest::AWAITING_BUYERS_APPROVAL])  ? 'disabled' : '' }}>
                                        Approve
                                    </button>
                                </form>

                                <form action="{{ route('request-reject', $assetRequest->meta_id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-danger mr-2" {{ !$assetTransferred || in_array($assetRequest->status, [\App\Models\AssetsRequest::RESOLVED. \App\Models\AssetsRequest::REJECTED, \App\Models\AssetsRequest::AWAITING_BUYERS_APPROVAL])  ? 'disabled' : '' }}>
                                        Reject
                                    </button>
                                </form>

                                <a href="{{ route('asset-requests') }}" class="btn btn-secondary mb-4">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('footer')
        </div>
    </div>
</div>
</body>


{{--
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
--}}


