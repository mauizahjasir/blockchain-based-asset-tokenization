@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Registration Successful</div>

                    <div class="card-body">
                        <div class="alert alert-success">
                            You have successfully registered an account!
                        </div>

                        <a href="{{ route('login') }}" class="btn btn-primary">Go to Login Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
