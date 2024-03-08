@extends('layouts.form')

@section('content')
<form action="{{ url('/items/store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-8 offset-2">
            <div class="row pt-3">
                <h1>Add New Item</h1>
            </div>
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Item Name</label>
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group row">
                <label for="price" class="col-md-4 col-form-label text-md-right">Price</label>
                <input id="price" type="number" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}"
                       name="price" value="{{ old('price') }}" autocomplete="price">
                @if ($errors->has('price'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('price') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group row">
                <label for="cost" class="col-md-4 col-form-label text-md-right">Cost</label>
                <input id="cost" type="number" class="form-control{{ $errors->has('cost') ? ' is-invalid' : '' }}"
                       name="cost" value="{{ old('cost') }}" autocomplete="cost">
                @if ($errors->has('cost'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('cost') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group row">
                <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                <textarea id="description" class="form-control" name="description"
                          autocomplete="description"></textarea>
            </div>
            <div class="row pt-4">
                <button class="btn btn-outline-primary">Add New Item</button>
            </div>
        </div>
    </div>
</form>
@endsection
