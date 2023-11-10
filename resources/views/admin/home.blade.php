@extends('layouts.default')  <!-- Replace 'your_layout_name' with the actual name of your layout file -->

@section('content')
    <div class="container mt-5">
        <h1>All Users</h1>
        <!-- Bootstrap Table -->
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Wallet address</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->user_type }}</td>
                    <td>{{ $user->wallet_address }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
