@extends('layouts.client.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">

        @include('alert')

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Asset Purchase Request Form</h4>
                        </div>
                        <div class="card-body">
                            <p>
                                Please fill out the form below to request the purchase of the <b>{{ $asset }}</b>.
                                Enter the amount you want to commit in exchange for the asset, along with any additional information about your request.
                            </p>

                            <!-- Purchase Request Form -->
                            <form method="POST" action="{{ route('request-purchase') }}">
                                @csrf

                                <!-- Asset ID (you may use a hidden field if needed) -->
                                <input type="hidden" name="asset" value="{{ $asset}}">

                                <!-- Amount to commit -->
                                <div class="form-group">
                                    <label for="commit_amount">Amount to Commit:</label>
                                    <input type="number" class="form-control" id="commit_amount" name="commit_amount" required>
                                </div>

                                <!-- Additional Information -->
                                <div class="form-group">
                                    <label for="additional_info">Additional Information:</label>
                                    <textarea class="form-control" id="additional_info" name="additional_info" rows="3"></textarea>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Submit Purchase Request</button>

                                <!-- Back button to go to the client.assets route -->
                                <a href="{{ route('bank-assets') }}" class="btn btn-secondary">Back</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
