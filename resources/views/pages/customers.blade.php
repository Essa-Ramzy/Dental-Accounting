@extends('layouts.table')
@section('head')
    @parent
    <script src="{{ asset('resources/js/views/pages/customers.js') }}"></script>
@endsection
@section('content')
    <!-- This is the layout for the customers table -->
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">Name</th>
            <th scope="col" class="text-center">Records</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
            <tr>
                <th scope="row">{{ $customer->id }}</th>
                <td>{{ $customer->updated_at->format('d-m-Y') }}</td>
                <td>{{ $customer->name }}</td>
                <!-- View the records of the customer in the entries table -->
                <td>
                    <a href="{{ route('Customer.records', ['id' => $customer->id]) }}" type="button"
                        class="btn btn-sm btn-info col-8 offset-2">View</a>
                </td>
                <!-- Edit and delete the customer -->
                <td>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('Customer.edit', ['id' => $customer->id]) }}" class="text-decoration-none">
                            <svg id="edit" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                                class="icon-link-hover">
                                <use href="#pencil-square" fill="none" stroke="var(--bs-body-color)" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
                            </svg>
                        </a>
                        <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal" id="{{ $customer->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                                <use href="#trash-fill" fill="var(--bs-body-color)"></use>
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th scope="row" colspan="4" class="text-md-center">Number of Customers: {{ $customers->count() }}</th>
            <td>
                <!-- Delete all the customers displayed in the table -->
                <div class="text-end">
                    <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                            <use href="#trash-fill" fill="var(--bs-body-color)"></use>
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
    </tfoot>
@endsection
@section('dropdown')
    <!-- This is the dropdown menu for the filter column search in the customers table -->
    <li class="dropdown-item">All</li>
    <li class="dropdown-item">ID</li>
    <li class="dropdown-item">Name</li>
@endsection
@section('modal')
    <!-- This is the delete modal for the customers table -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="Delete">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Delete Selected Customer(s)?</h5>
                    <p class="mb-0">You can always change your mind. All the entries related to this customer will be
                        deleted as well. Are you Sure?</p>
                </div>
                <form class="modal-footer flex-nowrap p-0" action="{{ route('Customer.delete') }}" method="post"
                    enctype="multipart/form-data">
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
