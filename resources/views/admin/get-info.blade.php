@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-9">
                <h1>Multichain Information</h1>

                <table class="table table-bordered">
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
