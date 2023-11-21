@extends('layouts.app')

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    @include('client.topbar')
    <div class="app-main">
        @include('client.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                @include('alert')
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-header">My Incoming Requests</div>
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Request for</th>
                                        <th>Your Approval</th>
                                        <th>Requestor's Approval</th>
                                        <th>Admin's Approval</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>

                                    @foreach ($assetsRequest as $assetRequest)
                                        <tr>
                                            <td>{{ $assetRequest->asset }}</td>
                                            <td>
                                                @if($assetRequest->isAwaitingOwnersApproval())

                                                    <form method="POST" action="{{ route('incoming-requests-approve', ['assetRequest' => $assetRequest->meta_id]) }}">
                                                        @csrf
                                                        <!-- Submit Button -->
                                                        <div class="mb-1 ml-1">
                                                            <button type="submit" class="btn btn-primary">Approve</button>
                                                        </div>
                                                    </form>
                                                    <div class="badge badge-warning">Pending</div>
                                                @else
                                                    <div class="badge badge-success">Done</div>
                                                @endif
                                            </td>

                                            <td>
                                                @if($assetRequest->isAwaitingRequestorsApproval())
                                                    <div class="badge badge-warning">Pending</div>
                                                @else
                                                    <div class="badge badge-success">Done</div>
                                                @endif
                                            </td>

                                            <td>
                                                @if($assetRequest->isResolved())
                                                    <div class="badge badge-success">Done</div>
                                                @else
                                                    <div class="badge badge-warning">Pending</div>
                                                @endif
                                            </td>

                                            <td>
                                                @if($assetRequest->isResolved())
                                                    <div class="badge badge-success">Done</div>
                                                @else
                                                    <div class="badge badge-warning">Pending</div>
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
