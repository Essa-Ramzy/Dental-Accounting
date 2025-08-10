@extends('layouts.form')
@section('content')
    <!-- This is the layout for adding a customer -->
    <form action="{{ route('Customer.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-8 offset-2">
                <div class="d-flex justify-content-center align-items-center pt-3 position-relative">
                    <a href="{{ $previousUrl }}" class="btn btn-outline-secondary position-absolute start-0">
                        ← Back
                    </a>
                    <h1 class="mb-0">Add New Customer</h1>
                </div>
                <!-- Name for the new customer -->
                <div class="form-group row m-0">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Customer Name</label>
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row pt-4 m-0">
                    <button class="btn btn-outline-primary">Add New Customer</button>
                </div>
            </div>
        </div>
    </form>
@endsection
