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
                                            decline the request:</b></div>

                                    <div><b>Requestor's name (Buyer):</b> {{ $assetRequest->requestor->name }}</div>
                                    <div><b>Owner's name (Sender):</b> {{ $assetRequest->owner->name }}</div>
                                    <div><b>Buyer's wallet address:</b> {{ $assetRequest->requestor->wallet_address }}
                                    </div>
                                    <div><b>Seller's wallet address:</b> {{ $assetRequest->owner->wallet_address }}
                                    </div>

                                    @if ($assetRequest->isRejected())
                                        <div class="badge badge-danger mt-1">Transaction reverted</div>
                                    @elseif ($assetRequest->isApproved())
                                        <div class="badge badge-danger mt-1">Transaction completed</div>
                                    @else
                                        @if ($assetRequest->isRejectedByBuyer())
                                            <div><b>Amount committed by buyer:</b>
                                                <div class="badge badge-danger">Buyer rejected transaction</div>
                                            </div>
                                        @else
                                            <div><b>Amount committed by buyer:</b> {{ $assetRequest->commit_amount }}
                                            </div>
                                        @endif
                                        <div><b>Has the seller transferred asset to my wallet? </b>
                                            @if ($assetTransferred)
                                                <div class="badge badge-success">Successfully transferred</div>
                                            @else
                                                <div class="badge badge-danger">Not transferred</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row mt-2 ml-4">
                                <form action="{{ route('assets.transactions.approve', $assetRequest->meta_id) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-success mr-2" {{ !$assetTransferred || $assetRequest->isPending() || in_array($assetRequest->status, [\App\Models\AssetsRequest::REJECTED_BY_OWNER, \App\Models\AssetsRequest::REJECTED_BY_BUYER])  ? 'disabled' : '' }}>
                                        Approve
                                    </button>
                                </form>

                                <form action="{{ route('assets.transactions.reject', $assetRequest->meta_id) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-danger mr-2" {{ (!$assetTransferred || $assetRequest->isPending()) && !in_array($assetRequest->status, [\App\Models\AssetsRequest::REJECTED_BY_OWNER, \App\Models\AssetsRequest::REJECTED_BY_BUYER])  ? 'disabled' : '' }}>
                                        Reject & Revert
                                    </button>
                                </form>

                                <a href="{{ route('assets.transactions') }}" class="btn btn-secondary mb-4">Back</a>
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
