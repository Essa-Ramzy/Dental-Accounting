@extends('layouts.app-with-header')
@section('head')
    @parent
    <meta name="search-url" content="{{ route($view . '.search') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/daterangepicker.min.css') }}">
    <script src="{{ asset('resources/js/moment.min.js') }}"></script>
    <script src="{{ asset('resources/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('resources/js/views/layouts/table.js') }}"></script>
@endsection
@section('layout')
    <!-- This is the layout for the table view -->
    <main class="d-flex flex-column flex-grow-1">
        <!-- Action bar with improved mobile responsiveness -->
        <div class="bg-dark-subtle border-bottom py-2">
            <div class="container-fluid">
                <div class="row g-2 align-items-center">
                    <!-- Add button - takes full width on mobile -->
                    <div class="col-md-10 col-12">
                        <a type="button" class="btn btn-primary w-100 w-md-auto" href="{{ route($view . '.create') }}">
                            <svg class="me-2 mb-1" width="16" height="16" fill="currentColor">
                                <use xlink:href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Add {{ $view }}
                        </a>
                    </div>
                    <!-- Filter toggle button -->
                    <div class="col-md-2 col-12">
                        <button class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center"
                            type="button" data-bs-toggle="collapse" data-bs-target="#filter" aria-expanded="false"
                            aria-controls="filter">
                            <svg class="me-2 mb-1" width="16" height="16">
                                <use href="#funnel" fill="currentColor" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter collapse menu with improved responsive layout -->
        <div class="collapse bg-body-secondary border-bottom" id="filter">
            <div class="container py-3">
                <div class="row g-3">
                    <!-- Date range filter -->
                    <div class="col-lg-6 col-12">
                        <label for="report_range" class="form-label fw-semibold">Date Range</label>
                        <input id="report_range" class="form-control text-start" readonly type="button"
                            value="Select Date Range">
                        <input hidden id="from_date" aria-label="from_date">
                        <input hidden id="to_date" aria-label="to_date">
                    </div>

                    <!-- Search filter -->
                    <div class="col-lg-6 col-12">
                        <label for="search" class="form-label fw-semibold">Search</label>
                        <div class="form-group p-0 w-100 d-flex align-items-end p-0 position-relative">
                            <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                                id="search" value="{{ Session::get('customer') ?? (Session::get('item') ?? '') }}">
                            <div class="dropdown position-absolute end-0 top-50 translate-middle-y d-flex">
                                <button id="dropdown_btn"
                                    class="btn btn-outline-secondary dropdown-toggle rounded-0 rounded-end border-0 border-start rounded-end-1"
                                    style="margin-right: calc(1 * var(--bs-border-width))" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Search by
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @yield('dropdown')
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table with improved responsive handling -->
        <div class="container-fluid table-responsive flex-grow-1">
            <table class="table table-striped table-hover m-0">
                @yield('content')
            </table>
        </div>
    </main>
    @yield('modal')
@endsection
