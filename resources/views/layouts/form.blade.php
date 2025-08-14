@extends('layouts.app-with-header')
@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('resources/css/views/layouts/form.css') }}">
@endsection
@section('layout')
    <!-- This is the layout for the form view -->
    <main class="container py-4">
        @if (Session('success'))
            <div class="alert alert-success alert-dismissible fade-out position-fixed top-0 start-50 p-2 m-3 w-50 d-flex align-items-center shadow-lg"
                role="status" aria-live="polite">
                <svg xmlns="http://www.w3.org/2000/svg" class="bi flex-shrink-0 me-2" role="img" aria-label="Success:"
                    width="20" height="20">
                    <use href="#check-circle-fill" fill="var(--bs-success)" />
                </svg>
                <div class="mx-auto">{{ Session('success') }}</div>
                <button type="button" class="btn-close p-2 m-1" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </main>
@endsection
