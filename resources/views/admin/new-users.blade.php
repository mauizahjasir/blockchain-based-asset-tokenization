@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('sidebar')
            <div class="col-md-9">
                <h1>Users</h1>

                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif

                @if (Session::has('errors'))
                    <div class="alert alert-danger">
                        @foreach(Session::get('errors') as $error)
                            {{ $error . "\n"}}
                        @endforeach
                    </div>
                @endif

                @if(!empty($users))
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->meta_id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->user_type }}</td>
                                <td>
                                    <form method="POST" action="{{ route('approve', ['user' => $user->meta_id]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            Approve
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">
                        No New Users
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
