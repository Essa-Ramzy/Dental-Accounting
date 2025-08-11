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
    <form action="{{ route('Entry.update', ['id' => $entry->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <input type="hidden" name="price" id="price" value="{{ $entry->unit_price }}">
        <input type="hidden" name="cost" id="cost" value="{{ $entry->cost }}">
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="d-flex justify-content-center align-items-center pt-3 position-relative">
                    <a href="{{ route('Entries') }}" class="btn btn-outline-secondary position-absolute start-0">
                        ← Back
                    </a>
                    <h1 class="mb-0">Edit Entry</h1>
                </div>
                <!-- Customer name for the new entry -->
                <div class="form-group row m-0">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Customer Name</label>
                    <select class="form-control selectpicker w-100 p-0{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        id="name" name="name"
                        data-style="{{ $errors->has('name') ? 'pe-calc invalid-focus-ring' : 'border focus-ring' }} text-body"
                        data-live-search="true" title="Select Customer" autofocus>
                        <option hidden></option>
                        @foreach ($customers as $customer)
                            <option
                                {{ (collect(old('name'))->contains($customer->name) or Session::get('createdCustomerId') == $customer->id or $customer->id == $entry->customer->id) ? 'selected' : '' }}
                                value="{{ $customer->name }}">{{ $customer->name }}</option>
                        @endforeach
                        <option value="">Create New Customer</option>
                    </select>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <!-- Item for the new entry -->
                <div class="form-group row m-0">
                    <label for="item" class="col-md-4 col-form-label text-md-right">Item</label>
                    <select class="form-control selectpicker w-100 p-0{{ $errors->has('item') ? ' is-invalid' : '' }}"
                        id="item" name="item"
                        data-style="{{ $errors->has('item') ? 'pe-calc invalid-focus-ring' : 'border focus-ring' }} text-body"
                        data-live-search="true" title="Select Item">
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
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('item') }}</strong>
                        </span>
                    @endif
                </div>
                <!-- Teeth selection with visual/list toggle -->
                <div class="form-group row m-0">
                    <label for="teeth" class="col-md-4 col-form-label text-md-right">Teeth</label>
                    <div class="col-md-6 w-100 p-0">
                        {{-- include our visual/list teeth selector --}}
                        <ul class="nav nav-tabs nav-fill" id="teethTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="visual-tab" type="button" data-target="#visual"
                                    aria-controls="visual" aria-selected="true">Visual</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="list-tab" type="button" data-target="#list"
                                    aria-controls="list" aria-selected="false">List</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-0 border rounded-bottom p-3 border-top-0" id="teethTabContent">
                            <div class="tab-pane show active" id="visual" role="tabpanel" aria-labelledby="visual-tab">
                                {{-- SVG Tooth Chart --}}
                                <div class="tooth-chart w-50 mx-auto">
                                    <x-teeth-visual :selectedTeeth="old('teeth', $entry->teeth_list)" />
                                </div>
                            </div>
                            <div class="tab-pane" id="list" role="tabpanel" aria-labelledby="list-tab">
                                <label for="teeth_list">Select Teeth</label>
                                <select
                                    class="form-control selectpicker w-100 p-0{{ $errors->has('teeth') ? ' is-invalid' : '' }}"
                                    id="teeth" name="teeth[]"
                                    data-style="{{ $errors->has('teeth') ? 'pe-calc invalid-focus-ring' : 'border focus-ring' }} text-body"
                                    data-live-search="true" title="Select Teeth" data-selected-text-format="count > 7"
                                    multiple>
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
                            </div>
                        </div>
                        @if ($errors->has('teeth'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('teeth') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <!-- Date for the new entry -->
                <div class="form-group row m-0">
                    <label for="date" class="col-md-4 col-form-label text-md-right">Date</label>
                    <input id="date" type="date" class="form-control" name="date"
                        value="{{ old('date', $entry->date->format('Y-m-d')) }}" autocomplete="date">
                </div>
                <!-- Discount for the new entry -->
                <div class="form-group row m-0">
                    <label for="discount" class="col-md-4 col-form-label text-md-right">Discount</label>
                    <div class="p-0 position-relative">
                        <input id="discount" type="number" step="0.01" placeholder="0.00"
                            class="form-control z-n1{{ $errors->has('discount') ? ' is-invalid' : '' }}" name="discount"
                            value="{{ old('discount', $entry->discount) }}" autocomplete="discount">
                        <div class="position-absolute end-0 top-50 translate-middle-y d-flex">
                            <button class="btn btn-outline-primary border-0 rounded-0 discount-mode-btn active" type="button"
                                data-mode="currency">E£</button>
                            <button class="btn btn-outline-primary border-0 rounded-0 rounded-end-1 discount-mode-btn"
                                type="button" data-mode="percent">%</button>
                        </div>
                        @if ($errors->has('discount'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('discount') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row m-0">
                    <label class="col-form-label text-md-right">Price Mode</label>
                    <div class="btn-group w-100 p-0" role="group" aria-label="Price mode toggle">
                        <button class="btn btn-outline-primary border border-end-0 price-mode-btn active" type="button"
                            data-mode="old">Old Price</button>
                        <button class="btn btn-outline-primary border border-start-0 price-mode-btn" type="button"
                            data-mode="new">New Price</button>
                    </div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Total</h5>
                        <p class="card-text text-center fs-5 fw-bold mb-0">
                            <span class="text-secondary">Unit Price:</span>
                            <span class="text-primary" id="receipt-unit-price">E£ 0</span>
                            <span class="mx-2">×</span>
                            <span class="text-secondary">Amount:</span>
                            <span class="text-primary" id="receipt-amount">0</span>
                            <span class="mx-2">-</span>
                            <span class="text-secondary">Discount:</span>
                            <span class="text-primary" id="receipt-discount">E£ 0</span>
                            <span class="mx-2">=</span>
                            <span class="text-success fw-bold" id="receipt-total">E£ 0</span>
                        </p>
                    </div>
                </div>
                <div class="row py-4 m-0">
                    <button type="submit" class="btn btn-outline-primary">Add New Entry</button>
                </div>
            </div>
        </div>
    </form>
@endsection
