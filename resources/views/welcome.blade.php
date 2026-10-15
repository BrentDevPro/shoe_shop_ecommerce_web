@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8 text-center">
            <h1 class="fw-bold">Welcome to the Shoe Shop</h1>
            <p class="lead">Your one-stop shop for the best sneakers.</p>
            
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary mt-3">Login to Shop</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary mt-3">Create Account</a>
            @else
                <a href="{{ url('/home') }}" class="btn btn-success mt-3">Go to Dashboard</a>
            @endguest
        </div>
    </div>
</div>
@endsection