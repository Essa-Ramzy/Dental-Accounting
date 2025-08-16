@extends('layouts.form')
@section('content')
    <!-- This is the layout for adding an item -->
    <!-- This is the layout for adding an item -->
    <form action="{{ route('Item.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ $previousUrl }}" class="btn btn-outline-secondary me-3" aria-label="Go back">
                        <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                            <use href="#arrow-left" fill="currentColor" />
                        </svg>
                        Back
                    </a>
                    <h1 class="h3 mb-0 fw-bold">Add New Item</h1>
                </div>

                <!-- Item Information Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h2 class="h6 mb-0">
                            <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                <use href="#tags" fill="currentColor" />
                            </svg>
                            Item Information
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Item Name -->
                            <div class="col-12 mb-3">
                                <label for="name" class="form-label fw-semibold">Item Name <span
                                        class="text-danger">*</span></label>
                                <input id="name" type="text"
                                    class="form-control form-control-lg{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    name="name" value="{{ old('name') }}" autocomplete="off" autofocus required
                                    placeholder="Enter treatment or service name">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <!-- Price and Cost -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-semibold">Price <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative{{ $errors->has('cost') ? ' is-invalid' : '' }}">
                                    <span
                                        class="input-group-text position-absolute top-50 start-0 translate-middle-y border-0 rounded-end-0 border-end"
                                        style="margin-left: calc(1 * var(--bs-border-width));">£</span>
                                    <input id="price" type="number" step="0.01" min="0"
                                        class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}"
                                        style="padding-left: calc(3.75 * 0.75rem);" name="price"
                                        value="{{ old('price') }}" required placeholder="0.00">
                                </div>
                                @if ($errors->has('price'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </div>
                                @endif
                                <div class="form-text">Customer-facing price</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cost" class="form-label fw-semibold">Cost <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative{{ $errors->has('cost') ? ' is-invalid' : '' }}">
                                    <span
                                        class="input-group-text position-absolute top-50 start-0 translate-middle-y border-0 rounded-end-0 border-end"
                                        style="margin-left: calc(1 * var(--bs-border-width));">£</span>
                                    <input id="cost" type="number" step="0.01" min="0"
                                        class="form-control{{ $errors->has('cost') ? ' is-invalid' : '' }}"
                                        style="padding-left: calc(3.75 * 0.75rem);" name="cost"
                                        value="{{ old('cost') }}" required placeholder="0.00">
                                </div>
                                @if ($errors->has('cost'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('cost') }}</strong>
                                    </div>
                                @endif
                                <div class="form-text">Internal cost for accounting</div>
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                    name="description" rows="4" placeholder="Enter detailed description of the treatment or service (optional)">{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </div>
                                @endif
                                <div class="form-text">Provide additional details about this item (optional)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ $previousUrl }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">
                        <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                            <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Add Item
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
