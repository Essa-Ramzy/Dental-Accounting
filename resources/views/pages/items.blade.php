@extends('layouts.table')
@section('head')
    @parent
    <script src="{{ asset('resources/js/views/pages/items.js') }}"></script>
@endsection
@section('svg-icons')
    @parent
    <symbol id="eye" viewBox="0 0 16 16">
        <path
            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z">
        </path>
        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"></path>
    </symbol>
    <symbol id="file-text" viewBox="0 0 16 16">
        <path
            d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5M5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1z">
        </path>
        <path
            d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1">
        </path>
    </symbol>
    <symbol id="clipboard2-plus" viewBox="0 0 16 16">
        <path
            d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z">
        </path>
        <path
            d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z">
        </path>
        <path d="M8.5 6.5a.5.5 0 0 0-1 0V8H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V9H10a.5.5 0 0 0 0-1H8.5z"></path>
    </symbol>
    <symbol id="clipboard2-check" viewBox="0 0 16 16">
        <path
            d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z">
        </path>
        <path
            d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z">
        </path>
        <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z">
        </path>
    </symbol>
@endsection
@section('content')
    <!-- This is the layout for the items table -->
    <thead>
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col">Date</th>
            <th scope="col" class="">Item Name</th>
            <th scope="col" class="text-end">Price</th>
            <th scope="col" class="text-end">Cost</th>
            <th scope="col" class="text-center">Description</th>
            <th scope="col" class="text-center">Records</th>
            <th scope="col" class="text-center" style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @include('pages.partials.items-body', ['items' => $items])
    </tbody>
    <tfoot>
        @include('pages.partials.items-footer', ['items' => $items, 'footer' => $footer])
    </tfoot>
@endsection
@section('dropdown')
    <!-- This is the dropdown menu for the filter column search options in the items table -->
    <li><button class="dropdown-item" type="button">All</button></li>
    <li><button class="dropdown-item" type="button">ID</button></li>
    <li><button class="dropdown-item" type="button">Name</button></li>
    <li><button class="dropdown-item" type="button">Price</button></li>
    <li><button class="dropdown-item" type="button">Cost</button></li>
@endsection
@section('modal')
    <!-- Single description modal -->
    <div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="descriptionModalLabel">
                        <svg width="24" height="24" class="me-1 mb-1" aria-hidden="true">
                            <use href="#file-text" fill="currentColor" />
                        </svg>
                        <span id="modalItemName">Item</span> - Description
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <div class="card p-0">
                        <div class="card-body">
                            <p class="mb-0" style="white-space: pre-line;" id="modalDescriptionText"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-primary" id="copyDescriptionBtn">
                        <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                            <use href="#clipboard2-plus" fill="currentColor" />
                        </svg>
                        Copy Description
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- This is the modal for deleting an item -->
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
                    <p class="mb-0">Are you sure you want to delete this/these item(s)? This action cannot be undone
                        and will also delete all related entries.</p>
                </div>
                <form action="{{ route('Item.delete') }}" method="post" enctype="multipart/form-data"
                    class="d-flex modal-footer p-0">
                    @csrf
                    @method('delete')
                    <!-- Hidden input in case of deleting several items -->
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
