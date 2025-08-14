@extends('layouts.form')
@section('head')
    @parent
    <meta name="customer-create-url" content="{{ route('Customer.create') }}">
    <meta name="item-create-url" content="{{ route('Item.create') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/views/components/visual-teeth.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/views/forms/entry.css') }}">
    <script src="{{ asset('resources/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('resources/js/views/forms/entry.js') }}"></script>
@endsection
@section('content')
    <!-- This is the layout for adding a new entry -->
    <form action="{{ route('Entry.store') }}" method="post" enctype="multipart/form-data" class="needs-validation"
        novalidate>
        @csrf
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11 col-12">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ $previous_url }}" class="btn btn-outline-secondary me-3" aria-label="Go back">
                        <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                            <use href="#arrow-left" fill="currentColor" />
                        </svg>
                        Back
                    </a>
                    <h1 class="h3 mb-0 fw-bold">Add New Entry</h1>
                </div>

                <div class="row g-4">
                    <!-- Entry Details Section -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white">
                                <h2 class="h6 mb-0">
                                    <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                        <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Entry Details
                                </h2>
                            </div>
                            <div class="card-body p-4">
                                <!-- Customer Selection -->
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold">Customer Name <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control selectpicker{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                        id="name" name="name"
                                        data-style="{{ $errors->has('name') ? 'pe-calc invalid-focus-ring' : 'border focus-ring' }} text-body"
                                        data-live-search="true" title="Select Customer" autofocus required>
                                        <option hidden></option>
                                        @foreach ($customers as $customer)
                                            <option
                                                {{ (collect(old('name'))->contains($customer->name) or Session::get('createdCustomerId') == $customer->id) ? 'selected' : '' }}
                                                value="{{ $customer->name }}">{{ $customer->name }}</option>
                                        @endforeach
                                        <option value="">Create New Customer</option>
                                    </select>
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                    <div class="form-text">Select an existing customer or create a new one</div>
                                </div>

                                <!-- Item Selection -->
                                <div class="mb-4">
                                    <label for="item" class="form-label fw-semibold">Treatment Item <span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control selectpicker{{ $errors->has('item') ? ' is-invalid' : '' }}"
                                        id="item" name="item"
                                        data-style="{{ $errors->has('item') ? 'pe-calc invalid-focus-ring' : 'border focus-ring' }} text-body"
                                        data-live-search="true" title="Select Item" required>
                                        @foreach ($items as $item)
                                            <option
                                                {{ (collect(old('item'))->contains($item->name) or Session::get('createdItemId') == $item->id) ? 'selected' : '' }}
                                                value="{{ $item->name }}" data-price="{{ $item->price }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                        <option value="">Create New Item</option>
                                    </select>
                                    @if ($errors->has('item'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('item') }}</strong>
                                        </div>
                                    @endif
                                    <div class="form-text">Select the treatment or service provided</div>
                                </div>

                                <!-- Date and Discount Row -->
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="date" class="form-label fw-semibold">Treatment Date</label>
                                        <input id="date" type="date" class="form-control" name="date"
                                            value="{{ old('date', date('Y-m-d')) }}" autocomplete="off">
                                        <div class="form-text">Date when the treatment was performed</div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="discount" class="form-label fw-semibold">Discount</label>
                                        <div
                                            class="p-0 position-relative{{ $errors->has('discount') ? ' is-invalid' : '' }}">
                                            <input id="discount" type="number" step="0.01" placeholder="0.00"
                                                class="form-control{{ $errors->has('discount') ? ' is-invalid' : '' }}"
                                                name="discount" value="{{ old('discount') }}" autocomplete="off">
                                            <div class="position-absolute end-0 top-50 translate-middle-y d-flex"
                                                role="group" aria-label="Discount type">
                                                <button
                                                    class="btn btn-outline-primary border-0 rounded-0 discount-mode-btn active"
                                                    type="button" data-mode="currency">£</button>
                                                <button
                                                    class="btn btn-outline-primary border-0 rounded-0 rounded-end-1 discount-mode-btn"
                                                    type="button" data-mode="percent">%</button>
                                            </div>
                                        </div>
                                        @if ($errors->has('discount'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('discount') }}</strong>
                                            </div>
                                        @endif
                                        <div class="form-text">Optional discount amount or percentage</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teeth Selection Section -->
                        <div class="card shadow-sm border-0 mt-4">
                            <div class="card-header bg-info text-white">
                                <h2 class="h6 mb-0">
                                    <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                        <use href="#grid-3x3-gap-fill" fill="currentColor" />
                                    </svg>
                                    Teeth Selection
                                </h2>
                            </div>
                            <div class="card-body p-0">
                                <!-- Tab Navigation -->
                                <ul class="nav nav-underline nav-fill m-0 gap-2" id="teethTab" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active py-3 fw-semibold" id="visual-tab" type="button"
                                            data-bs-toggle="tab" data-bs-target="#visual" aria-controls="visual"
                                            aria-selected="true">
                                            <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                                                <use href="#eye" fill="currentColor" />
                                            </svg>
                                            Visual Chart
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link py-3 fw-semibold" id="list-tab" type="button"
                                            data-bs-toggle="tab" data-bs-target="#list" aria-controls="list"
                                            aria-selected="false">
                                            <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                                                <use href="#list-ul" fill="currentColor" />
                                            </svg>
                                            List Selection
                                        </button>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content p-4" id="teethTabContent">
                                    <div class="tab-pane show active" id="visual" role="tabpanel"
                                        aria-labelledby="visual-tab">
                                        <div class="text-center mb-3">
                                            <p class="text-muted mb-2">Click on teeth to select them for treatment</p>
                                        </div>
                                        <div class="d-flex justify-content-center mb-3">
                                            <div class="btn-group btn-group-sm" role="group"
                                                aria-label="Selection tools">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="select-all-teeth" title="Select All Teeth">
                                                    <svg width="14" height="14" class="me-1 mb-1"
                                                        aria-hidden="true">
                                                        <use href="#check-all" fill="currentColor" />
                                                    </svg>
                                                    Select All
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="clear-all-teeth" title="Clear All Selections">
                                                    <svg width="14" height="14" class="me-1 mb-1"
                                                        aria-hidden="true">
                                                        <use href="#x-circle" fill="currentColor" />
                                                    </svg>
                                                    Clear All
                                                </button>
                                            </div>
                                        </div>
                                        <div class="tooth-chart mx-auto w-50">
                                            <x-teeth-visual :selectedTeeth="old('teeth')" />
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="list" role="tabpanel" aria-labelledby="list-tab">
                                        <label for="teeth" class="form-label fw-semibold mb-3">Select Teeth</label>
                                        <select
                                            class="form-control selectpicker{{ $errors->has('teeth') ? ' is-invalid' : '' }}"
                                            id="teeth" name="teeth[]"
                                            data-style="{{ $errors->has('teeth') ? 'pe-calc invalid-focus-ring' : 'border focus-ring' }} text-body"
                                            data-live-search="true" title="Select Teeth"
                                            data-selected-text-format="count > 5" multiple>
                                            @foreach ([['UR-', 'Upper Right'], ['UL-', 'Upper Left'], ['LR-', 'Lower Right'], ['LL-', 'Lower Left']] as $mouth_part)
                                                <optgroup label="{{ $mouth_part[1] }}">
                                                    @for ($i = 1; $i <= 8; $i++)
                                                        <option
                                                            {{ collect(old('teeth'))->contains($mouth_part[0] . $i) ? 'selected' : '' }}
                                                            value="{{ $mouth_part[0] . $i }}"
                                                            title="{{ $mouth_part[1] . ' ' . $i }}">
                                                            {{ $mouth_part[0] . $i }}</option>
                                                    @endfor
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <div class="form-text mt-2">Select one or more teeth</div>
                                    </div>
                                </div>
                                @if ($errors->has('teeth'))
                                    <div class="alert alert-danger mx-4 mb-4">
                                        <strong>{{ $errors->first('teeth') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Receipt Summary Section -->
                    <div class="col-lg-4">
                        <div class="sticky-top" style="top: 2rem;">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-success text-white">
                                    <h2 class="h6 mb-0">
                                        <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                            <use href="#receipt-cutoff" fill="currentColor" />
                                        </svg>
                                        Treatment Summary
                                    </h2>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Unit Price:</span>
                                        <span class="fw-bold" id="receipt-unit-price">£ 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Amount:</span>
                                        <span class="fw-bold" id="receipt-amount">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Discount:</span>
                                        <span class="fw-bold text-danger" id="receipt-discount">£ 0</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0">Total:</span>
                                        <span class="h4 mb-0 fw-bold text-success" id="receipt-total">£ 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ $previous_url }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5">
                                <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                                    <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Add Entry
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
