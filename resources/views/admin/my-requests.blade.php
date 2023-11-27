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
                            <div class="card-header">My Requests</div>
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Request for</th>
                                        <th>Requested By</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    @foreach ($assetsRequest as $assetRequest)
                                        <tr>
                                            <td>{{ $assetRequest->asset }}</td>
                                            <td>{{ $assetRequest->requestor->name }}</td>

                                            <td>
                                                <form method="GET"
                                                      action="{{ route('admin-request-details', ['assetRequest' => $assetRequest->meta_id]) }}">
                                                    @csrf
                                                    <!-- Submit Button -->
                                                    <div class="mt-2">
                                                        <button type="submit" class="btn btn-primary">Request Details
                                                        </button>
                                                    </div>
                                                </form>
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
