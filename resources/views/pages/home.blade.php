@extends('layouts.app-with-header')
@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('resources/css/views/layouts/home.css') }}">
@endsection
@section('svg-icons')
    <symbol id="grid-plus" viewBox="0 0 16 16">
        <path
            d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z">
        </path>
        <circle cx="13" cy="3" r="3" fill="currentColor" />
        <line x1="13" y1="1.5" x2="13" y2="4.5" stroke="white" stroke-linecap="round" />
        <line x1="11.5" y1="3" x2="14.5" y2="3" stroke="white" stroke-linecap="round" />
    </symbol>
    <symbol id="tags-plus" viewBox="0 0 16 16">
        <path
            d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z">
        </path>
        <path
            d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z">
        </path>
        <circle cx="13" cy="3" r="3" fill="currentColor" />
        <line x1="13" y1="1.5" x2="13" y2="4.5" stroke="white" stroke-linecap="round" />
        <line x1="11.5" y1="3" x2="14.5" y2="3" stroke="white" stroke-linecap="round" />
    </symbol>
    <symbol id="people-circle-plus" viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
        <path fill-rule="evenodd"
            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z">
        </path>
        <circle cx="13" cy="3" r="3" fill="currentColor" />
        <line x1="13" y1="1.5" x2="13" y2="4.5" stroke="white" stroke-linecap="round" />
        <line x1="11.5" y1="3" x2="14.5" y2="3" stroke="white" stroke-linecap="round" />
    </symbol>
    <symbol id="book-open" viewBox="0 0 24 24">
        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
    </symbol>
    <symbol id="plus-circle" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="16"></line>
        <line x1="8" y1="12" x2="16" y2="12"></line>
    </symbol>
@endsection
@section('layout')
    <!-- Hero Cover Section -->
    <div class="position-relative overflow-hidden min-vh-90">
        <!-- Background Image -->
        <div class="position-absolute top-0 start-0 w-100 h-100" id="hero-bg"
            style="background-image: url('{{ asset('resources/img/hero-bg.png') }}');" role="img"
            aria-label="Dental clinic background">
        </div>
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50" aria-hidden="true">
        </div>
        <!-- Content -->
        <div class="container position-absolute top-50 start-50 translate-middle h-100 d-flex align-items-center"
            id="hero-content">
            <div class="row align-items-center justify-content-center w-100">
                <div class="col-lg-10 col-xl-8 text-center text-white">
                    <h1 class="display-4 display-md-3 fw-bold mb-4 text-shadow">Dental Clinic Accounting</h1>
                    <p class="lead mb-5 fs-5 fs-md-4 text-white-75">
                        Streamline your dental practice management with our comprehensive accounting solution.
                        Track patient records, manage treatments, and monitor financial performance all in one place.
                    </p>
                    <div class="d-flex flex-wrap gap-2 gap-md-3 justify-content-center mb-5">
                        <span class="badge bg-success bg-shadow text-white px-3 px-md-4 py-2 py-md-3 fs-6 rounded-pill">
                            <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                <use href="#people-circle" fill="currentColor"></use>
                            </svg>
                            Patient Management
                        </span>
                        <span class="badge bg-info bg-shadow text-white px-3 px-md-4 py-2 py-md-3 fs-6 rounded-pill">
                            <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                <use href="#grid" fill="currentColor"></use>
                            </svg>
                            Treatment Tracking
                        </span>
                        <span class="badge bg-warning bg-shadow text-dark px-3 px-md-4 py-2 py-md-3 fs-6 rounded-pill">
                            <svg width="18" height="18" class="me-2 mb-1" aria-hidden="true">
                                <use href="#tags" fill="currentColor"></use>
                            </svg>
                            Financial Reports
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container py-5">
        <!-- View Data Section -->
        <div class="text-center mb-5">
            <h2 class="h3 fw-bold text-body-emphasis mb-2">
                <svg width="24" height="24" class="me-2 text-primary" aria-hidden="true">
                    <use href="#book-open" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"></use>
                </svg>
                View Data
            </h2>
            <p class="text-body-secondary">Access and review your dental practice records</p>
            <hr class="w-25 mx-auto border-primary border-2 opacity-75">
        </div>

        <div class="row g-4 mb-5">
            <!-- Entries -->
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('Entries') }}"
                    class="card h-100 text-decoration-none shadow-sm border-0 rounded-4 overflow-hidden position-relative card-hover"
                    aria-label="View all patient entries and treatments">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-primary" aria-hidden="true">
                                <use href="#grid" fill="currentColor"></use>
                            </svg>
                        </div>
                        <h3 class="card-title h5 fw-bold text-body-emphasis mb-2">Entries</h3>
                        <p class="text-body-secondary small mb-0">View all patient entries and treatments</p>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-primary">View</span>
                    </div>
                </a>
            </div>

            <!-- Items -->
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('Items') }}"
                    class="card h-100 text-decoration-none shadow-sm border-0 rounded-4 overflow-hidden position-relative card-hover"
                    aria-label="Manage treatment items and services">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-success" aria-hidden="true">
                                <use href="#tags" fill="currentColor"></use>
                            </svg>
                        </div>
                        <h3 class="card-title h5 fw-bold text-body-emphasis mb-2">Items</h3>
                        <p class="text-body-secondary small mb-0">Manage treatment items and services</p>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-success">View</span>
                    </div>
                </a>
            </div>

            <!-- Customers -->
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('Customers') }}"
                    class="card h-100 text-decoration-none shadow-sm border-0 rounded-4 overflow-hidden position-relative card-hover"
                    aria-label="Browse patient database and records">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-info" aria-hidden="true">
                                <use href="#people-circle" fill="currentColor"></use>
                            </svg>
                        </div>
                        <h3 class="card-title h5 fw-bold text-body-emphasis mb-2">Customers</h3>
                        <p class="text-body-secondary small mb-0">Browse patient database and records</p>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-info">View</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Add New Section -->
        <div class="text-center my-5">
            <h2 class="h3 fw-bold text-body-emphasis mb-2">
                <svg width="24" height="24" class="me-2 text-success" aria-hidden="true">
                    <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"></use>
                </svg>
                Add New Records
            </h2>
            <p class="text-body-secondary">Create new entries for your dental practice</p>
            <hr class="w-25 mx-auto border-success border-2 opacity-75">
        </div>

        <div class="row g-4 mb-5">
            <!-- Add Entry -->
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('Entry.create') }}"
                    class="card h-100 text-decoration-none shadow-sm border-0 rounded-4 overflow-hidden position-relative card-hover"
                    aria-label="Record new patient treatment">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-primary" aria-hidden="true">
                                <use href="#grid-plus" fill="currentColor"></use>
                            </svg>
                        </div>
                        <h3 class="card-title h5 fw-bold text-body-emphasis mb-2">Add Entry</h3>
                        <p class="text-body-secondary small mb-0">Record new patient treatment</p>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-primary">Add</span>
                    </div>
                </a>
            </div>

            <!-- Add Item -->
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('Item.create') }}"
                    class="card h-100 text-decoration-none shadow-sm border-0 rounded-4 overflow-hidden position-relative card-hover"
                    aria-label="Create new treatment or service">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-success" aria-hidden="true">
                                <use href="#tags-plus" fill="currentColor"></use>
                            </svg>
                        </div>
                        <h3 class="card-title h5 fw-bold text-body-emphasis mb-2">Add Item</h3>
                        <p class="text-body-secondary small mb-0">Create new treatment or service</p>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-success">Add</span>
                    </div>
                </a>
            </div>

            <!-- Add Customer -->
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('Customer.create') }}"
                    class="card h-100 text-decoration-none shadow-sm border-0 rounded-4 overflow-hidden position-relative card-hover"
                    aria-label="Register new patient profile">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-info" aria-hidden="true">
                                <use href="#people-circle-plus" fill="currentColor"></use>
                            </svg>
                        </div>
                        <h3 class="card-title h5 fw-bold text-body-emphasis mb-2">Add Customer</h3>
                        <p class="text-body-secondary small mb-0">Register new patient profile</p>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-info">Add</span>
                    </div>
                </a>
            </div>
        </div>
    </main>
@endsection
