@extends('layouts.store')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-6 d-none d-lg-block bg-light position-relative">
                        <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?q=80&w=800&auto=format&fit=crop" alt="Sneaker Collection" class="img-fluid h-100 w-100" style="object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 w-100 p-5" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                            <h3 class="text-white fw-bold mb-1">Step Into Excellence.</h3>
                            <p class="text-white-50 mb-0">Discover original performance sneakers.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center bg-white">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold tracking-tight text-dark mb-1">Welcome Back</h2>
                            <p class="text-muted small">Sign in to access your orders and fast checkout.</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold text-uppercase small tracking-wide text-muted ms-2">Email Address</label>
                                <input id="email" type="email" class="form-control form-control-lg rounded-pill px-4 bg-light border-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="font-size: 0.95rem;">
                                @error('email')
                                    <span class="invalid-feedback ms-3" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center ms-2">
                                    <label for="password" class="form-label fw-bold text-uppercase small tracking-wide text-muted mb-0">Password</label>
                                    @if (Route::has('password.request'))
                                        <a class="text-decoration-none small text-dark fw-medium" href="{{ route('password.request') }}">
                                            Forgot password?
                                        </a>
                                    @endif
                                </div>
                                <input id="password" type="password" class="form-control form-control-lg rounded-pill px-4 bg-light border-0 mt-2 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="font-size: 0.95rem;">
                                @error('password')
                                    <span class="invalid-feedback ms-3" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4 ms-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted small fw-medium" for="remember">
                                        Keep me signed in
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark btn-lg rounded-pill w-100 py-3 fw-bold shadow-sm mb-4">
                                Sign In
                            </button>

                            <p class="text-center text-muted small mb-0">
                                Don't have an account? <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none border-bottom border-dark">Create one now</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection