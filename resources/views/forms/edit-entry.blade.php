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
    <!-- This is the layout for editing an entry -->
    <form action="{{ route('Entry.update', ['id' => $entry->id]) }}" method="post" enctype="multipart/form-data"
        class="needs-validation" novalidate>
        @csrf
        @method('PATCH')
        <input type="hidden" name="unit_price" id="price" value="{{ old('unit_price', $entry->unit_price) }}">
        <input type="hidden" name="cost" id="cost" value="{{ old('cost', $entry->unit_cost) }}">

        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11 col-12">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('Entries') }}" class="btn btn-outline-secondary me-3"
                        aria-label="Go back to entries">
                        <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                            <use xlink:href="#arrow-left" fill="currentColor" />
                        </svg>
                        Back
                    </a>
                    <h1 class="h3 mb-0 fw-bold">Edit Entry</h1>
                </div>

                <div class="row g-4">
                    <!-- Entry Details Section -->
                    <div class="col-lg-8">
                        <!-- Entry Information Card -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h2 class="h6 mb-0">
                                    <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                        <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Update Entry Information
                                </h2>
                            </div>
                            <div class="card-body p-4">
                                <!-- Entry Metadata -->
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Entry ID</label>
                                        <div class="bg-secondary-subtle rounded p-2 border border-secondary-subtle">
                                            <strong>#{{ $entry->id }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Created Date</label>
                                        <div class="bg-secondary-subtle rounded p-2 border border-secondary-subtle">
                                            {{ $entry->created_at->format('M j, Y') }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Original Total</label>
                                        <div class="bg-secondary-subtle rounded p-2 border border-secondary-subtle">
                                            <strong>£
                                                {{ number_format($entry->price, strlen(rtrim(substr(strrchr($entry->price, '.'), 1), '0'))) }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Selection -->
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold">Customer Name <span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control selectpicker{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                        id="name" name="name"
                                        data-style="{{ $errors->has('name') ? 'pe-calc invalid-focus-ring' : 'border focus-ring' }} text-body"
                                        data-live-search="true" title="Select Customer" autofocus required>
                                        <option hidden></option>
                                        @foreach ($customers as $customer)
                                            <option
                                                {{ (collect(old('name'))->contains($customer->name) or Session::get('createdCustomerId') == $customer->id or $customer->id == $entry->customer->id) ? 'selected' : '' }}
                                                value="{{ $customer->name }}">{{ $customer->name }}</option>
                                        @endforeach
                                        <option value="">Create New Customer</option>
                                    </select>
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                    <div class="form-text">Current: <strong>{{ $entry->customer->name }}</strong></div>
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
                                                {{ (collect(old('item'))->contains($item->name) or Session::get('createdItemId') == $item->id or $item->id == $entry->item->id) ? 'selected' : '' }}
                                                value="{{ $item->name }}"
                                                {{ $item->id == $entry->item->id ? 'data-price=' . $entry->unit_price . ' data-old-price=' . $entry->unit_price . ' data-new-price=' . $item->price . ' data-old-cost=' . $entry->unit_cost . ' data-new-cost=' . $item->cost : 'data-price=' . $item->price }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                        <option value="">Create New Item</option>
                                    </select>
                                    @if ($errors->has('item'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('item') }}</strong>
                                        </div>
                                    @endif
                                    <div class="form-text">Current: <strong>{{ $entry->item->name }}</strong></div>
                                </div>

                                <!-- Date and Discount Row -->
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="date" class="form-label fw-semibold">Treatment Date</label>
                                        <input id="date" type="date" class="form-control" name="date"
                                            value="{{ old('date', $entry->date->format('Y-m-d')) }}" autocomplete="off">
                                        <div class="form-text">Date when the treatment was performed</div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="discount" class="form-label fw-semibold">Discount</label>
                                        <div
                                            class="p-0 position-relative{{ $errors->has('discount') ? ' is-invalid' : '' }}">
                                            <input id="discount" type="number" step="0.01" placeholder="0.00"
                                                class="form-control{{ $errors->has('discount') ? ' is-invalid' : '' }}"
                                                name="discount"
                                                value="{{ number_format(old('discount', $entry->discount), strlen(rtrim(substr(strrchr(old('discount', $entry->discount), '.'), 1), '0')), '.', '') }}"
                                                autocomplete="off">
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
                                        <div class="form-text">Current discount:
                                            £
                                            {{ number_format($entry->discount, strlen(rtrim(substr(strrchr($entry->discount, '.'), 1), '0'))) }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Price Mode -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Price Mode</label>
                                    <div class="btn-group w-100" role="group" aria-label="Price mode toggle">
                                        <button class="btn btn-outline-primary price-mode-btn active" type="button"
                                            data-mode="old">Use Original Price</button>
                                        <button class="btn btn-outline-primary price-mode-btn" type="button"
                                            data-mode="new">Use Current Price</button>
                                    </div>
                                    <div class="form-text">Choose whether to keep original pricing or update to current
                                        rates</div>
                                </div>
                            </div>
                        </div>

                        <!-- Teeth Selection Section -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-info text-white">
                                <h2 class="h6 mb-0">
                                    <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                        <use href="#grid-3x3-gap-fill" fill="currentColor" />
                                    </svg>
                                    Update Teeth Selection
                                </h2>
                            </div>
                            <div class="card-body p-0">
                                <!-- Tab Navigation -->
                                <ul class="nav nav-underline nav-fill border-0 m-0 gap-2" id="teethTab" role="tablist">
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
                                            <p class="text-muted mb-2">Click on teeth to select/deselect them for treatment
                                            </p>
                                            <div class="alert alert-info py-2">
                                                <small><strong>Currently selected:</strong>
                                                    {{ str_replace(['UR', 'UL', 'LR', 'LL'], ['Upper Right', 'Upper Left', 'Lower Right', 'Lower Left'], $entry->teeth) }}</small>
                                            </div>
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
                                            <x-teeth-visual :selectedTeeth="old('teeth', $entry->teeth_list)" />
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
                                                            {{ (collect(old('teeth'))->contains($mouth_part[0] . $i) or $entry->teeth_list->contains($mouth_part[0] . $i)) ? 'selected' : '' }}
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
                                            <use xlink:href="#receipt-cutoff" fill="currentColor" />
                                        </svg>
                                        Updated Summary
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

                                    <!-- Original vs Updated -->
                                    <div class="mt-4 pt-3 border-top">
                                        <div class="d-flex justify-content-between align-items-center text-muted small">
                                            <span>Original Total:</span>
                                            <span>£
                                                {{ number_format($entry->price, strlen(rtrim(substr(strrchr($entry->price, '.'), 1), '0'))) }}</span>
                                        </div>
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
                            <a href="{{ route('Entries') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-warning px-5">
                                <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                                    <use xlink:href="#check-lg" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Update Entry
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
