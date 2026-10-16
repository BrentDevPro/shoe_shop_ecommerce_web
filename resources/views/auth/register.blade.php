@extends('layouts.store')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                <div class="row g-0 flex-lg-row-reverse">
                    <div class="col-lg-6 d-none d-lg-block bg-light position-relative">
                        <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?q=80&w=800&auto=format&fit=crop" alt="Sneaker Details" class="img-fluid h-100 w-100" style="object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 w-100 p-5" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                            <h3 class="text-white fw-bold mb-1">Join the Community.</h3>
                            <p class="text-white-50 mb-0">Exclusive drops and fast checkouts await.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center bg-white">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold tracking-tight text-dark mb-1">Create Account</h2>
                            <p class="text-muted small">Fill in your details to get started.</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold text-uppercase small tracking-wide text-muted ms-2">Full Name</label>
                                <input id="name" type="text" class="form-control form-control-lg rounded-pill px-4 bg-light border-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus style="font-size: 0.95rem;">
                                @error('name')
                                    <span class="invalid-feedback ms-3" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold text-uppercase small tracking-wide text-muted ms-2">Email Address</label>
                                <input id="email" type="email" class="form-control form-control-lg rounded-pill px-4 bg-light border-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="font-size: 0.95rem;">
                                @error('email')
                                    <span class="invalid-feedback ms-3" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <label for="password" class="form-label fw-bold text-uppercase small tracking-wide text-muted ms-2">Password</label>
                                    <input id="password" type="password" class="form-control form-control-lg rounded-pill px-4 bg-light border-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" style="font-size: 0.95rem;">
                                    @error('password')
                                        <span class="invalid-feedback ms-3" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="password-confirm" class="form-label fw-bold text-uppercase small tracking-wide text-muted ms-2">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control form-control-lg rounded-pill px-4 bg-light border-0" name="password_confirmation" required autocomplete="new-password" style="font-size: 0.95rem;">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark btn-lg rounded-pill w-100 py-3 fw-bold shadow-sm mb-4">
                                Create Account
                            </button>

                            <p class="text-center text-muted small mb-0">
                                Already have an account? <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none border-bottom border-dark">Sign in here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection