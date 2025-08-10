@extends('layouts.form')
@section('content')
    <!-- This is the layout for editing an item -->
    <form action="{{ route('Item.update', ['id' => $item->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-8 offset-2">
                <div class="d-flex justify-content-center align-items-center pt-3 position-relative">
                    <a href="{{ route('Items') }}" class="btn btn-outline-secondary position-absolute start-0">
                        ← Back
                    </a>
                    <h1 class="mb-0">Edit Item</h1>
                </div>
                <!-- New name of the item -->
                <div class="form-group row m-0">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Item Name</label>
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        name="name" value="{{ old('name') ?? $item->name }}" autocomplete="name" autofocus>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <!-- New price of the item -->
                <div class="form-group row m-0">
                    <label for="price" class="col-md-4 col-form-label text-md-right">Price</label>
                    <input id="price" type="text"
                        class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price"
                        value="{{ old('price') ?? $item->price }}" autocomplete="price" autofocus>
                    @if ($errors->has('price'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('price') }}</strong>
                        </span>
                    @endif
                </div>
                <!-- New cost of the item -->
                <div class="form-group row m-0">
                    <label for="cost" class="col-md-4 col-form-label text-md-right">Cost</label>
                    <input id="cost" type="text" class="form-control{{ $errors->has('cost') ? ' is-invalid' : '' }}"
                        name="cost" value="{{ old('cost') ?? $item->cost }}" autocomplete="cost" autofocus>
                    @if ($errors->has('cost'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('cost') }}</strong>
                        </span>
                    @endif
                </div>
                <!-- New description of the item -->
                <div class="form-group row m-0">
                    <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                    <textarea id="description" type="text" class="form-control" name="description" autocomplete="description" autofocus>{{ old('description') ?? $item->description }}</textarea>
                </div>
                <div class="row pt-4 m-0">
                    <button class="btn btn-outline-primary">Edit Item</button>
                </div>
            </div>
        </div>
    </form>
@endsection
