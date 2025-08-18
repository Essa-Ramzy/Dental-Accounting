@extends('layouts.table')
@section('content')
    <!-- This is the layout for the customers table -->
    <thead>
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col">Date</th>
            <th scope="col" class="">Customer Name</th>
            <th scope="col" class="text-center">Records</th>
            <th scope="col" class="text-center" style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @include('pages.partials.customers-body', ['customers' => $customers])
    </tbody>
    <tfoot>
        @include('pages.partials.customers-footer', ['customers' => $customers, 'footer' => $footer])
    </tfoot>
@endsection
@section('dropdown')
    <!-- This is the dropdown menu for the filter column search in the customers table -->
    <li><button class="dropdown-item" type="button">All</button></li>
    <li><button class="dropdown-item" type="button">ID</button></li>
    <li><button class="dropdown-item" type="button">Name</button></li>
@endsection
@section('modal')
    <!-- This is the delete modal for the customers table -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger" id="deleteModalLabel">
                        <svg width="24" height="24" class="me-2 mb-1" aria-hidden="true">
                            <use href="#trash-fill" fill="currentColor" />
                        </svg>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="mb-0">Are you sure you want to delete this/these customer(s)? This action cannot be undone
                        and will also delete all related entries.</p>
                </div>
                <form action="{{ route('Customer.delete') }}" method="post" enctype="multipart/form-data"
                    class="d-flex modal-footer p-0">
                    @csrf
                    @method('delete')
                    <!-- Hidden input in case of deleting several customers -->
                    <input hidden aria-label="delete_filter" name="filter">
                    <input hidden aria-label="delete_search" name="search">
                    <input hidden aria-label="delete_from_date" name="from_date">
                    <input hidden aria-label="delete_to_date" name="to_date">
                    <button type="submit"
                        class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end">
                        <strong>Yes, Delete</strong></button>
                    <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0"
                        data-bs-dismiss="modal">No thanks
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
