@extends('layouts.admin.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">

        @include('alert')

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Multichain Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                    <th>Key</th>
                                    <th>Value</th>
                                    </thead>
                                    <tbody>

                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $value }}</td>
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
