@extends('layouts.admin')

@section('content')
<style>
    .metric-card { border-radius: 0.75rem; border: none; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04); }
    .tracking-wide { letter-spacing: 0.05em; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Account Settings</h3>
        <p class="text-muted small mb-0">Manage your admin profile and security preferences.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Left Column: Form --}}
    <div class="col-lg-8">
        <div class="card metric-card bg-white">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark mb-0">Profile Information</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.update') }}" method="POST">
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
                    </div>

                    <hr class="border-light-subtle my-5">
                    
                    <h6 class="fw-bold text-dark mb-2">Change Password</h6>
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

                    <div class="text-end mt-4 pt-3">
                        <button type="submit" class="btn btn-dark rounded-pill px-5 py-2 fw-medium shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Right Column: User Profile Badge --}}
    <div class="col-lg-4">
        <div class="card metric-card bg-white">
            <div class="card-body p-4 text-center mt-3">
                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-3">System Administrator</p>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-medium mb-4" style="font-size: 0.75rem;">Active Account</span>
                <hr class="border-light-subtle">
                <p class="text-muted small lh-base mb-0 text-start mt-3">
                    <i class="bi bi-shield-check me-2 text-primary"></i> As an administrator, you have full access to manage inventory, orders, and customer details. Ensure your password remains secure.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection