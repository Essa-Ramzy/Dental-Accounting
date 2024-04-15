@extends('layouts.form')
<!-- This is the layout for adding a customer -->
@section('content')
    <form action="{{ route('Customer.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-8 offset-2">
                <div class="row pt-3">
                    <h1>Add New Customer</h1>
                </div>
                <!-- Name for the new customer -->
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Customer Name</label>
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                           name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                    @endif
                </div>
                <div class="row pt-4">
                    <button class="btn btn-outline-primary">Add New Customer</button>
                </div>
            </div>
        </div>
    </form>
@endsection
