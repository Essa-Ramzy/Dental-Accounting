@extends('layouts.app-with-header')
@section('head')
    <meta name="search-url" content="{{ route($view . '.search') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/daterangepicker.min.css') }}">
    <script src="{{ asset('resources/js/moment.min.js') }}"></script>
    <script src="{{ asset('resources/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('resources/js/views/layouts/table.js') }}"></script>
@endsection
@section('layout')
    <!-- This is the layout for the table view -->
    <main class="d-flex flex-column flex-grow-1">
        <div class="px-3 py-2 border-bottom w-100">
            <div class="container d-flex">
                <!-- Button to add a new row to the table -->
                <div class="me-2 w-100">
                    <a type="button" class="btn btn-outline-primary w-100" href="{{ route($view . '.create') }}">
                        Add {{ $view }}
                    </a>
                </div>
                <!-- Filter collapse button that displays the filter options -->
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filter" aria-expanded="false" aria-controls="filter">Filter
                </button>
            </div>
            <!-- Filter collapse menu -->
            <div class="row collapse container p-3 border rounded mt-2 mx-auto" id="filter">
                <!-- Filter date range -->
                <div class="col-md-6 form-group p-0 pe-2" id="date_range_filter">
                    <label for="report_range" class="col-md-4 pt-0 col-form-label text-md-right">Date Range</label>
                    <input id="report_range" class="text-body bg-body btn btn-secondary border w-100 text-start" readonly
                        type="button" value="Date Range">
                    <input hidden id="from_date" aria-label="from_date">
                    <input hidden id="to_date" aria-label="to_date">
                </div>
                <!-- Filter column search -->
                <div class="col-md-6 form-group p-0 ps-2">
                    <!-- Search bar for the filter column -->
                    <label for="search" class="col-md-4 pt-0 col-form-label text-md-right">Search</label>
                    <div class="form-group p-0 w-100 d-flex align-items-end p-0 position-relative">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="search"
                            value="{{ Session::get('customer') ?? (Session::get('item') ?? '') }}">
                        <div class="dropdown position-absolute end-0 top-50 translate-middle-y d-flex">
                            <!-- Filter dropdown button that displays the filter column search options -->
                            <button id="dropdown_btn"
                                class="btn btn-secondary dropdown-toggle rounded-0 rounded-end border-0 rounded-end-1"
                                style="margin-right: calc(1 * var(--bs-border-width))" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">Search by
                            </button>
                            <!-- Filter column search dropdown menu -->
                            <ul class="dropdown-menu ">
                                @yield('dropdown')
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table to display the data -->
        <div class="container-fluid table-responsive flex-grow-1">
            <table class="table table-striped m-0">
                @yield('content')
            </table>
        </div>
    </main>
    @yield('modal')
@endsection
