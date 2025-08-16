@extends('layouts.form')
@section('content')
    <!-- This is the layout for editing a customer -->
    <form action="{{ route('Customer.update', ['id' => $customer->id]) }}" method="post" enctype="multipart/form-data"
        class="needs-validation" novalidate>
        @csrf
        @method('PATCH')
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('Customers') }}" class="btn btn-outline-secondary me-3"
                        aria-label="Go back to customers">
                        <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                            <use href="#arrow-left" fill="currentColor" />
                        </svg>
                        Back
                    </a>
                    <h1 class="h3 mb-0 fw-bold">Edit Customer</h1>
                </div>

                <!-- Customer Information Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h2 class="h6 mb-0">
                            <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Update Customer Information
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <!-- Customer Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Customer Name <span
                                    class="text-danger">*</span></label>
                            <input id="name" type="text"
                                class="form-control form-control-lg{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                name="name" value="{{ old('name', $customer->name) }}" autocomplete="name" autofocus
                                required placeholder="Enter customer's full name">
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </div>
                            @endif
                            <div class="form-text">
                                Current name: <strong>{{ $customer->name }}</strong>
                            </div>
                        </div>

                        <!-- Customer ID Display -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Customer ID</label>
                            <div
                                class="form-control-plaintext bg-secondary-subtle rounded p-2 border border-secondary-subtle">
                                <strong>#{{ $customer->id }}</strong>
                            </div>
                        </div>

                        <!-- Registration Date -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Registration Date</label>
                            <div
                                class="form-control-plaintext bg-secondary-subtle rounded p-2 border border-secondary-subtle">
                                {{ $customer->created_at->format('F j, Y \a\t	 g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('Customers') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning px-4">
                        <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                            <use href="#check-lg" fill="currentColor" />
                        </svg>
                        Update Customer
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
