@extends('layouts.app-with-header')
@section('head')
    <link rel="stylesheet" href="{{ asset('resources/css/views/layouts/form.css') }}">
@endsection
@section('layout')
    <!-- This is the layout for the form view -->
    <main class="container">
        @if (Session('success'))
            <div class="alert alert-success alert-dismissible fade-out position-fixed top-0 start-50 m-3 w-75" role="alert">
                <div class="text-center fs-5">{{ Session('success') }}</div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </main>
@endsection
