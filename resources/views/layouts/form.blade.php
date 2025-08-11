@extends('layouts.app-with-header')
@section('head')
    <link rel="stylesheet" href="{{ asset('resources/css/views/layouts/form.css') }}">
@endsection
@section('layout')
    <!-- This is the layout for the form view -->
    <main class="container">
        @if (Session('success'))
            <div class="alert alert-success alert-dismissible fade-out position-fixed top-0 start-50 p-2 m-3 w-75 d-flex align-items-center"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="var(--bs-success)" class="bi flex-shrink-0 me-2" role="img"
                    aria-label="Success:" width="24" height="24" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
                <div class="mx-auto">{{ Session('success') }}</div>
                <button type="button" class="btn-close p-2 m-1" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </main>
@endsection
