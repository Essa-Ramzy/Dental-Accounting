@extends('layouts.form')

@section('content')
<form action="{{ url('/store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-8 offset-2">
            <div class="row pt-3">
                <h1>Add New Entry</h1>
            </div>
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Customer Name</label>
                <select class="form-control selectpicker w-100 p-0{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        id="name" name="name" data-style="btn-outline-secondary text-white" data-live-search="true"
                        value="{{ old('name') ?? '' }}" title="Select Customer" autofocus>
                    <option hidden></option>
                    @foreach($customers as $customer)
                    <option {{ (collect(old(
                    'name'))->contains($customer->name)) ? 'selected':'' }}
                    value="{{ $customer->name }}">{{ $customer->name }}</option>
                    @endforeach
                    <option value="create_customer">Create New Customer</option>
                </select>
                @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group row">
                <label for="item" class="col-md-4 col-form-label text-md-right">Item</label>
                <select class="form-control selectpicker w-100 p-0{{ $errors->has('item') ? ' is-invalid' : '' }}"
                        id="item" name="item" data-style="btn-outline-secondary text-white" data-live-search="true"
                        value="{{ old('item') }}" title="Select Item">
                    @foreach($items as $item)
                    <option {{ (collect(old(
                    'item'))->contains($item->name)) ? 'selected':'' }}
                    value="{{ $item->name }}">{{ $item->name }}</option>
                    @endforeach
                    <option value="create_item">Create New Item</option>
                </select>
                @if ($errors->has('item'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('item') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group row">
                <label for="teeth" class="col-md-4 col-form-label text-md-right">Teeth</label>
                <select class="form-control selectpicker w-100 p-0{{ $errors->has('teeth') ? ' is-invalid' : '' }}"
                        id="teeth" name="teeth[]" multiple data-style="btn-outline-secondary text-white"
                        data-live-search="true" title="Select Teeth" data-selected-text-format="count > 7">
                    @foreach(['TR-Top Right', 'TL-Top Left', 'BR-Bottom Right', 'BL-Bottom-Left'] as $mouth_part)
                    <optgroup label="{{ substr($mouth_part, 3) }}">
                        @for ($i = 1; $i <= 8; $i++)
                        <option {{ collect(old(
                        'teeth'))->contains(substr($mouth_part, 0, 2) . $i) ? 'selected':'' }}
                        value="{{ substr($mouth_part, 0, 2) . $i }}" title="{{ substr($mouth_part, 3) . '-' . $i }}">
                        {{ substr($mouth_part, 0, 2) . '-' . $i }}</option>
                        @endfor
                    </optgroup>
                    @endforeach
                </select>
                @if ($errors->has('teeth'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('teeth') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group row">
                <label for="date" class="col-md-4 col-form-label text-md-right">Date</label>
                <input id="date" type="date" class="form-control" name="date" autocomplete="date">
            </div>
            <div class="form-group row">
                <label for="discount" class="col-md-4 col-form-label text-md-right">Discount</label>
                <input id="discount" type="number"
                       class="form-control{{ $errors->has('discount') ? ' is-invalid' : '' }}"
                       name="discount" value="{{ old('discount') | 0 }}" autocomplete="discount">
                @if ($errors->has('discount'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('discount') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group row">
                <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                <textarea id="description" type="text" class="form-control" name="description"
                          autocomplete="description"></textarea>
            </div>
            <div class="row pt-4">
                <button class="btn btn-outline-primary">Add New Entry</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('js')
<script>
    document.getElementById('name').addEventListener('change', (e) => {
        if (e.target.value === 'create_customer') {
            window.location = "{{ url('/customers/create') }}"
        }
    })

    document.getElementById('item').addEventListener('change', (e) => {
        if (e.target.value === 'create_item') {
            window.location = "{{ url('/items/create') }}"
        }
    })

    $('#name.selectpicker').selectpicker(
        {
            noneResultsText: `<a href="{{ url('/customers/create') }}" class="bg-body d-block text-center p-2" style="margin: -4px;">Create New Customer</a>`
        }
    );

    $('#item.selectpicker').selectpicker(
        {
            noneResultsText: `<a href="{{ url('/items/create') }}" class="bg-body d-block text-center p-2" style="margin: -4px;">Create New Item</a>`
        }
    );

    $('#teeth.selectpicker').selectpicker(
        {
            noneResultsText: '<span class="bg-body d-block p-2" style="margin: -3px;">No results matched</span>'
        }
    );
</script>
@endsection
