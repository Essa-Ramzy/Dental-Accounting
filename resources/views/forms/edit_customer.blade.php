@extends('layouts.form')

@section('content')
    <form action="{{ route('Customer.update', ['id' => $customer->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-8 offset-2">
                <div class="row pt-3">
                    <h1>Edit Item</h1>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Item Name</label>
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                           name="name" value="{{ old('name') ?? $customer->name }}" autocomplete="name" autofocus>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                    @endif
                </div>
                <div class="row pt-4">
                    <button class="btn btn-outline-primary">Edit Item</button>
                </div>
            </div>
        </div>
    </form>
@endsection
