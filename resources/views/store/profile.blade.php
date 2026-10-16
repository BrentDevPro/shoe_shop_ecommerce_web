@extends('layouts.store')

@section('content')
<div class="container my-5">
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="fw-bold tracking-tight mb-1">Account Settings</h1>
            <p class="text-muted">Manage your personal information, address, and security.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
                <h4 class="fw-bold mb-4 text-dark">Profile Information</h4>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-pill bg-light border-0 px-4 py-2 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback ms-3">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-pill bg-light border-0 px-4 py-2 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback ms-3">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Default Shipping Address</label>
                            <textarea name="address" class="form-control rounded-4 bg-light border-0 p-3 @error('address') is-invalid @enderror" rows="3" placeholder="E.g., 123 Main St, Tarlac City, Philippines">{{ old('address', $user->address ?? '') }}</textarea>
                            <small class="text-muted ms-2 mt-1 d-block">This address will automatically fill in during checkout.</small>
                            @error('address') <div class="invalid-feedback ms-3">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="border-light-subtle my-5">

                    <h4 class="fw-bold mb-3 text-dark">Security Settings</h4>
                    <p class="text-muted small mb-4">Leave these fields blank if you do not want to change your password.</p>

                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Current Password</label>
                            <input type="password" name="current_password" class="form-control rounded-pill bg-light border-0 px-4 py-2 @error('current_password') is-invalid @enderror">
                            @error('current_password') <div class="invalid-feedback ms-3">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">New Password</label>
                            <input type="password" name="new_password" class="form-control rounded-pill bg-light border-0 px-4 py-2 @error('new_password') is-invalid @enderror">
                            @error('new_password') <div class="invalid-feedback ms-3">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control rounded-pill bg-light border-0 px-4 py-2">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-dark btn-lg rounded-pill px-5 fw-bold shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-light p-4 text-center sticky-top" style="top: 100px;">
                <div class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3 shadow-sm border border-light-subtle" style="width: 80px; height: 80px; font-size: 2.2rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-4">Customer Member</p>
                
                <a href="{{ route('orders.index') }}" class="btn btn-outline-dark rounded-pill w-100 mb-3 fw-medium">
                    <i class="bi bi-box me-2"></i> View My Orders
                </a>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger text-decoration-none w-100 fw-medium">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection