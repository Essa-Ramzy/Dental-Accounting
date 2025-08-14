@extends('layouts.table')
@section('head')
    @parent
    <script src="{{ asset('resources/js/views/pages/customers.js') }}"></script>
@endsection
@section('content')
    <!-- This is the layout for the customers table -->
    <thead>
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col">Date</th>
            <th scope="col">Customer Name</th>
            <th scope="col" class="text-center">Records</th>
            <th scope="col" class="text-center" style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($customers as $customer)
            <tr class="align-middle">
                <th scope="row" class="text-center text-muted">{{ $customer->id }}</th>
                <td class="text-muted">
                    {{ $customer->updated_at->format('M d, Y') }}
                </td>
                <td>
                    <div class="fw-semibold">{{ $customer->name }}</div>
                </td>
                <td class="text-center">
                    <a href="{{ route('Customer.records', ['id' => $customer->id]) }}"
                        class="btn btn-sm btn-outline-primary">
                        <span class="badge bg-primary rounded-pill me-1">{{ $customer->entries->count() }}</span>
                        View Records
                    </a>
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group" aria-label="Customer actions">
                        <a href="{{ route('Customer.edit', ['id' => $customer->id]) }}"
                            class="btn btn-sm btn-outline-primary border-secondary border-end-0"
                            aria-label="Edit {{ $customer->name }}">
                            <svg width="20" height="20" aria-hidden="true">
                                <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <a href="#deleteModal" data-bs-toggle="modal" id="{{ $customer->id }}"
                            class="btn btn-sm btn-outline-danger border-secondary border-start-0"
                            aria-label="Delete {{ $customer->name }}">
                            <svg width="24" height="24" aria-hidden="true">
                                <use href="#trash-fill" fill="currentColor" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-5 text-muted">
                    <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                        <use href="#people-circle" fill="currentColor" />
                    </svg>
                    <div class="h5">No customers found</div>
                    <p class="mb-3">Get started by adding your first customer.</p>
                    <a href="{{ route('Customer.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" class="me-1 mb-1" aria-hidden="true">
                            <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Add Customer
                    </a>
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th scope="row" colspan="4" class="text-center fw-semibold">
                Total Customers: {{ $customers->count() }}
            </th>
            <td class="text-center">
                <div class="btn-group" role="group" aria-label="Customer actions">
                    <a href="{{ route('Customer.create') }}"
                        class="btn btn-sm btn-outline-success border-secondary border-end-0" aria-label="Add new customer">
                        <svg width="20" height="20" aria-hidden="true">
                            <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <a class="btn btn-sm btn-outline-danger border-secondary border-start-0" data-bs-toggle="modal"
                        href="#deleteModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                            <use href="#trash-fill" fill="currentColor"></use>
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
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
