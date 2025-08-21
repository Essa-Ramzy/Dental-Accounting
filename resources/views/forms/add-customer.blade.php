@extends('layouts.form')
@section('svg-icons')
    @parent
    <symbol id="plus-circle" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="16"></line>
        <line x1="8" y1="12" x2="16" y2="12"></line>
    </symbol>
@endsection
@section('content')
    <!-- This is the layout for adding a customer -->
    <form action="{{ route('Customer.store') }}" method="post" enctype="multipart/form-data" class="needs-validation"
        novalidate>
        @csrf
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ $previousUrl }}" class="btn btn-outline-secondary me-3 d-flex align-items-center gap-2" aria-label="Go back">
                        <svg width="16" height="16" aria-hidden="true">
                            <use href="#arrow-left" fill="currentColor" />
                        </svg>
                        Back
                    </a>
                    <h1 class="h3 mb-0 fw-bold">Add New Customer</h1>
                </div>

                <!-- Customer Information Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h6 mb-0 d-flex align-items-center gap-2">
                            <svg width="18" height="18" aria-hidden="true">
                                <use href="#people-circle" fill="currentColor" />
                            </svg>
                            Customer Information
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <!-- Customer Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Customer Name <span
                                    class="text-danger">*</span></label>
                            <input id="name" type="text"
                                class="form-control form-control-lg{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                name="name" value="{{ old('name') }}" autocomplete="name" autofocus required
                                placeholder="Enter customer's full name">
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </div>
                            @endif
                            <div class="form-text">
                                Enter the full name of the customer as it should appear in records.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ $previousUrl }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2">
                        <svg width="16" height="16" aria-hidden="true">
                            <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Add Customer
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
