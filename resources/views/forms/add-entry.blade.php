@extends('layouts.form')
@section('head')
    <meta name="customer-create-url" content="{{ route('Customer.create') }}">
    <meta name="item-create-url" content="{{ route('Item.create') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/views/visual-teeth.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/bootstrap-select.min.css') }}">
    <script src="{{ asset('resources/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('resources/js/views/add-entry.js') }}"></script>
@endsection
@section('content')
    <!-- This is the layout for adding a new entry -->
    <form action="{{ route('Entry.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-8 offset-2">
                <div class="row pt-3">
                    <h1>Add New Entry</h1>
                </div>
                <!-- Customer name for the new entry -->
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Customer Name</label>
                    <select class="form-control selectpicker w-100 p-0{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        id="name" name="name" data-style="border focus-ring text-body" data-live-search="true"
                        value="{{ old('name') ?? '' }}" title="Select Customer" autofocus>
                        <option hidden></option>
                        @foreach ($customers as $customer)
                            <option
                                {{ (collect(old('name'))->contains($customer->name) or Session::get('createdCustomerId') == $customer->id) ? 'selected' : '' }}
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
                <div class="form-group row">
                    <label for="item" class="col-md-4 col-form-label text-md-right">Item</label>
                    <select class="form-control selectpicker w-100 p-0{{ $errors->has('item') ? ' is-invalid' : '' }}"
                        id="item" name="item" data-style="border focus-ring text-body" data-live-search="true"
                        value="{{ old('item') }}" title="Select Item">
                        @foreach ($items as $item)
                            <option
                                {{ (collect(old('item'))->contains($item->name) or Session::get('createdItemId') == $item->id) ? 'selected' : '' }}
                                value="{{ $item->name }}">{{ $item->name }}</option>
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
                <div class="form-group row">
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
                                    <x-teeth-visual :selectedTeeth="old('teeth')" />
                                </div>
                            </div>
                            <div class="tab-pane" id="list" role="tabpanel" aria-labelledby="list-tab">
                                <label for="teeth_list">Select Teeth</label>
                                <select
                                    class="form-control selectpicker w-100 p-0{{ $errors->has('teeth') ? ' is-invalid' : '' }}"
                                    id="teeth" name="teeth[]" data-style="border focus-ring text-body"
                                    data-live-search="true" title="Select Teeth" data-selected-text-format="count > 7"
                                    multiple>
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
                <div class="form-group row">
                    <label for="date" class="col-md-4 col-form-label text-md-right">Date</label>
                    <input id="date" type="date" class="form-control" name="date" autocomplete="date">
                </div>
                <!-- Discount for the new entry -->
                <div class="form-group row">
                    <label for="discount" class="col-md-4 col-form-label text-md-right">Discount</label>
                    <input id="discount" type="number" placeholder="0"
                        class="form-control{{ $errors->has('discount') ? ' is-invalid' : '' }}" name="discount"
                        value="{{ old('discount') }}" autocomplete="discount">
                    @if ($errors->has('discount'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('discount') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row py-4">
                    <button type="submit" class="btn btn-outline-primary">Add New Entry</button>
                </div>
            </div>
        </div>
    </form>
@endsection
