@extends('layouts.client.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">

        @include('alert');

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Bank Assets</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Units</th>
                                    <th>Type</th>
                                    </thead>
                                    <tbody>

                                    @foreach ($assets as $asset)
                                        <tr>
                                            <td>{{ $asset['name'] }}</td>
                                            <td>{{ $asset['qty'] }}</td>
                                            <td>{{ $asset['info'] && $asset['info']['units'] ? $asset['info']['units'] : '' }}</td>
                                            <td>{{ $asset['info'] && $asset['info']['details'] ? $asset['info']['details']['type'] ?? '' : '' }}</td>
                                            {{--<td>
                                                <form method="GET" action="{{ route('request-purchase', ['asset' => $asset->meta_id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                                    <button type="submit" class="btn btn-primary" style="height: 30px; font-size: 12px">Submit Buy Request</button>
                                                </form>
                                            </td>--}}
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
