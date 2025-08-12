@extends('layouts.app-with-header')
@section('head')
    <link rel="stylesheet" href="{{ asset('resources/css/views/layouts/home.css') }}">
@endsection
@section('layout')
    <!-- Hero Cover Section -->
    <div class="position-relative overflow-hidden min-vh-90">
        <!-- Background Image -->
        <div class="position-absolute top-0 start-0 w-100 h-100" id="hero-bg"
            style="background-image: url('{{ asset('resources/img/hero-bg.png') }}');">
        </div>
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50">
        </div>
        <!-- Content -->
        <div class="container position-absolute top-50 start-50 translate-middle h-100 d-flex align-items-center"
            id="hero-content">
            <div class="row align-items-center justify-content-center w-100">
                <div class="col-lg-8 text-center text-white">
                    <h1 class="display-3 fw-bold mb-4 text-shadow">Dental Clinic Accounting</h1>
                    <p class="lead mb-5 fs-4 text-white-75">
                        Streamline your dental practice management with our comprehensive accounting solution.
                        Track patient records, manage treatments, and monitor financial performance all in one place.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center mb-5">
                        <span class="badge bg-success bg-shadow text-white px-4 py-3 fs-6 rounded-pill">
                            <svg width="16" height="16" class="me-2">
                                <use href="#user" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
                            </svg>
                            Patient Management
                        </span>
                        <span class="badge bg-info bg-shadow text-white px-4 py-3 fs-6 rounded-pill">
                            <svg width="16" height="16" class="me-2">
                                <use href="#file-text" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
                            </svg>
                            Treatment Tracking
                        </span>
                        <span class="badge bg-warning bg-shadow text-dark px-4 py-3 fs-6 rounded-pill">
                            <svg width="16" height="16" class="me-2">
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
                <svg width="24" height="24" class="me-2 text-primary">
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
                    class="card h-100 text-decoration-none shadow border-0 rounded-4 overflow-hidden position-relative card-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-primary">
                                <use href="#file-text" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
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
                    class="card h-100 text-decoration-none shadow border-0 rounded-4 overflow-hidden position-relative card-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-success">
                                <use href="#tag" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
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
                    class="card h-100 text-decoration-none shadow border-0 rounded-4 overflow-hidden position-relative card-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-info">
                                <use href="#user" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
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
                <svg width="24" height="24" class="me-2 text-success">
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
                    class="card h-100 text-decoration-none shadow border-0 rounded-4 overflow-hidden position-relative card-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-primary">
                                <use href="#file-plus" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
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
                    class="card h-100 text-decoration-none shadow border-0 rounded-4 overflow-hidden position-relative card-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-success">
                                <use href="#tag-plus" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
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
                    class="card h-100 text-decoration-none shadow border-0 rounded-4 overflow-hidden position-relative card-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 mb-3">
                            <svg width="48" height="48" class="text-info">
                                <use href="#user-plus" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
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
