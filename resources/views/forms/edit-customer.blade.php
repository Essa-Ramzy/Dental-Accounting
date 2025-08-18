@extends('layouts.form')
@section('svg-icons')
    @parent
    <symbol id="pencil-square" viewBox="0 0 24 24">
        <path
            d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z">
        </path>
        <path
            d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13">
        </path>
    </symbol>
@endsection
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
