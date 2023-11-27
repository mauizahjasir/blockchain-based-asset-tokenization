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
                                    <div class="mb-3"><b>Please review the following details and choose to accept or decline the request:</b></div>

                                    <div><b>Requestor's name (Buyer):</b> {{ $assetRequest->requestor->name }}</div>
                                    <div><b>Buyer's wallet address:</b> {{ $assetRequest->requestor->wallet_address }}
                                    </div>
                                    <div><b>Amount committed by buyer:</b> {{ $assetRequest->commit_amount }}</div>
                                </div>
                            </div>

                            <div class="row mt-2 ml-4">
                                <form action="{{ route('admin-request-approve', $assetRequest->meta_id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-success mr-2">
                                        Approve
                                    </button>
                                </form>

                                <form action="{{ route('admin-request-reject', $assetRequest->meta_id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-danger mr-2">
                                        Reject & Revert
                                    </button>
                                </form>

                                <a href="{{ route('my-requests') }}" class="btn btn-secondary mb-4">Back</a>
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

