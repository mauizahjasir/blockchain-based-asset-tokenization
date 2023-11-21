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
                            <div class="card-header">Pending Requests</div>
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Request for</th>
                                        <th>Owner's Approval</th>
                                        <th>Requestor's Approval</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    @foreach ($assetsRequest as $assetRequest)
                                        <tr>
                                            <td>{{ $assetRequest->asset }}</td>
                                            <td>
                                                @if($assetRequest->isRejectedByOwner() || $assetRequest->isRejectedByBuyer())
                                                    <div class="badge badge-danger">Rejected</div>
                                                @elseif($assetRequest->isApprovedByOwner())
                                                    <div class="badge badge-success">Done</div>
                                                @else
                                                    <div class="badge badge-warning">Pending</div>
                                                @endif
                                            </td>

                                            <td>
                                                @if($assetRequest->isRejectedByOwner() || $assetRequest->isRejectedByBuyer())
                                                    <div class="badge badge-danger">Rejected</div>
                                                @elseif($assetRequest->isAwaitingAdminsApproval())
                                                    <div class="badge badge-success">Done</div>
                                                @else
                                                    <div class="badge badge-warning">Pending</div>
                                                @endif
                                            </td>

                                            <td>
                                                @if($assetRequest->isResolved())
                                                    <div class="badge badge-success">Approved</div>
                                                @elseif ($assetRequest->isRejected())
                                                    <div class="badge badge-danger">Rejected</div>
                                                @else
                                                    <form method="GET"
                                                          action="{{ route('request-details', ['assetRequest' => $assetRequest->meta_id]) }}">
                                                        @csrf
                                                        <!-- Submit Button -->
                                                        <div class="mt-2">
                                                            <button type="submit" class="btn btn-primary">Verify
                                                                Request
                                                            </button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
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
