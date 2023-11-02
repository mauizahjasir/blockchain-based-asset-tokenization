@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('sidebar')
            <div class="col-md-9">
                <h1>Get Multichain Information</h1>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
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
@endsection
