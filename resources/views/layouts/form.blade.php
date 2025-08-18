@extends('layouts.app-with-header')
@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('resources/css/views/layouts/form.css') }}">
@endsection
@section('svg-icons')
    <symbol id="check-circle-fill" viewBox="0 0 16 16">
        <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z">
        </path>
    </symbol>
    <symbol id="arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
            d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8">
        </path>
    </symbol>
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
