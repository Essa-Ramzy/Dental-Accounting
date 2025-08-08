@extends('layouts.table')
@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('resources/css/views/visual-teeth.css') }}">
    <script src="{{ asset('resources/js/views/entries.js') }}"></script>
@endsection
@section('content')
    <!-- This is the layout for the entries table -->
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">Name</th>
            <th scope="col">Item</th>
            <th scope="col">Teeth</th>
            <th scope="col">Amount</th>
            <th scope="col">Unit Price</th>
            <th scope="col">Discount</th>
            <th scope="col">Price</th>
            <th scope="col">Cost</th>
            <th scope="col">
                <div class="dropdown text-end">
                    <!-- Button to export the table data -->
                    <button type="button" class="btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false"
                        data-bs-auto-close="outside" id="export_btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V11C19 11.5523 19.4477 12 20 12C20.5523 12 21 11.5523 21 11V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM10.3078 23.5628C10.4657 23.7575 10.6952 23.9172 10.9846 23.9762C11.2556 24.0316 11.4923 23.981 11.6563 23.9212C11.9581 23.8111 12.1956 23.6035 12.3505 23.4506C12.5941 23.2105 12.8491 22.8848 13.1029 22.5169C14.2122 22.1342 15.7711 21.782 17.287 21.5602C18.1297 21.4368 18.9165 21.3603 19.5789 21.3343C19.8413 21.6432 20.08 21.9094 20.2788 22.1105C20.4032 22.2363 20.5415 22.3671 20.6768 22.4671C20.7378 22.5122 20.8519 22.592 20.999 22.6493C21.0755 22.6791 21.5781 22.871 22.0424 22.4969C22.3156 22.2768 22.5685 22.0304 22.7444 21.7525C22.9212 21.4733 23.0879 21.0471 22.9491 20.5625C22.8131 20.0881 22.4588 19.8221 22.198 19.6848C21.9319 19.5448 21.6329 19.4668 21.3586 19.4187C21.11 19.3751 20.8288 19.3478 20.5233 19.3344C19.9042 18.5615 19.1805 17.6002 18.493 16.6198C17.89 15.76 17.3278 14.904 16.891 14.1587C16.9359 13.9664 16.9734 13.7816 17.0025 13.606C17.0523 13.3052 17.0824 13.004 17.0758 12.7211C17.0695 12.4497 17.0284 12.1229 16.88 11.8177C16.7154 11.4795 16.416 11.1716 15.9682 11.051C15.5664 10.9428 15.1833 11.0239 14.8894 11.1326C14.4359 11.3004 14.1873 11.6726 14.1014 12.0361C14.0288 12.3437 14.0681 12.6407 14.1136 12.8529C14.2076 13.2915 14.4269 13.7956 14.6795 14.2893C14.702 14.3332 14.7251 14.3777 14.7487 14.4225C14.5103 15.2072 14.1578 16.1328 13.7392 17.0899C13.1256 18.4929 12.4055 19.8836 11.7853 20.878C11.3619 21.0554 10.9712 21.2584 10.6746 21.4916C10.4726 21.6505 10.2019 21.909 10.0724 22.2868C9.9132 22.7514 10.0261 23.2154 10.3078 23.5628ZM11.8757 23.0947C11.8755 23.0946 11.8775 23.0923 11.8824 23.0877C11.8783 23.0924 11.8759 23.0947 11.8757 23.0947ZM16.9974 19.5812C16.1835 19.7003 15.3445 19.8566 14.5498 20.0392C14.9041 19.3523 15.2529 18.6201 15.5716 17.8914C15.7526 17.4775 15.9269 17.0581 16.0885 16.6431C16.336 17.0175 16.5942 17.3956 16.8555 17.7681C17.2581 18.3421 17.6734 18.911 18.0759 19.4437C17.7186 19.4822 17.3567 19.5287 16.9974 19.5812ZM16.0609 12.3842C16.0608 12.3829 16.0607 12.3823 16.0606 12.3823C16.0606 12.3822 16.0607 12.3838 16.061 12.3872C16.061 12.386 16.0609 12.385 16.0609 12.3842Z"
                                fill="#FFFFFF" />
                        </svg>
                    </button>
                    <!-- Export column options -->
                    <div class="dropdown-menu py-2 px-3">
                        <form action="{{ route('Entry.export') }}" method="post" class="top-0"
                            enctype="multipart/form-data" target="_blank">
                            @csrf
                            <!-- Hidden inputs for exporting the filtered data -->
                            <input hidden aria-label="export_filter" name="filter">
                            <input hidden aria-label="export_search" name="search">
                            <input hidden aria-label="export_from_date" name="from_date">
                            <input hidden aria-label="export_to_date" name="to_date">
                            <!-- Export column options -->
                            <div class="form-check form-switch">
                                <input name="id" class="form-check-input" type="checkbox" role="switch" id="id"
                                    value="ID" checked>
                                <label class="form-check-label" for="id">ID</label>
                            </div>
                            <div class="form-check form-switch">
                                <input name="date" class="form-check-input" type="checkbox" role="switch" id="date"
                                    value="Date" checked>
                                <label class="form-check-label" for="date">Date</label>
                            </div>
                            <div class="form-check form-switch">
                                <input name="name" class="form-check-input" type="checkbox" role="switch" id="name"
                                    value="Name" checked>
                                <label class="form-check-label" for="name">Name</label>
                            </div>
                            <div class="form-check form-switch">
                                <input name="item" class="form-check-input" type="checkbox" role="switch" id="item"
                                    value="Item" checked>
                                <label class="form-check-label" for="item">Item</label>
                            </div>
                            <div class="form-check form-switch">
                                <input name="teeth" class="form-check-input" type="checkbox" role="switch" id="teeth"
                                    value="Teeth" checked>
                                <label class="form-check-label" for="teeth">Teeth</label>
                            </div>
                            <div class="form-check form-switch">
                                <input name="amount" class="form-check-input" type="checkbox" role="switch" id="amount"
                                    value="Amount" checked>
                                <label class="form-check-label" for="amount">Amount</label>
                            </div>
                            <div class="form-check form-switch">
                                <input name="unit_price" class="form-check-input" type="checkbox" role="switch"
                                    id="unit_price" value="Unit Price" checked>
                                <label class="form-check-label" for="unit_price">Unit Price</label>
                            </div>
                            <div class="form-check form-switch">
                                <input name="discount" class="form-check-input" type="checkbox" role="switch"
                                    id="discount" value="Discount" checked>
                                <label class="form-check-label" for="discount">Discount</label>
                            </div>
                            <div class="form-check form-switch">
                                <input name="price" class="form-check-input" type="checkbox" role="switch"
                                    id="price" value="Price" checked>
                                <label class="form-check-label" for="price">Price</label>
                            </div>
                            <div class="form-check form-switch">
                                <input name="cost" class="form-check-input" type="checkbox" role="switch"
                                    id="cost" value="Cost" checked>
                                <label class="form-check-label" for="cost">Cost</label>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-outline-success w-100 mt-2">Export</button>
                            </div>
                        </form>
                    </div>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($entries as $entry)
            <tr>
                <th scope="row">{{ $entry->id }}</th>
                <td>{{ $entry->date->format('d-m-Y') }}</td>
                <td>{{ $entry->customer->name }}</td>
                <td>{{ $entry->item->name }}</td>
                <td>
                    <span role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                        data-bs-content-target="toothTooltip{{ $entry->id }}">
                        {{ $entry->teeth }}
                    </span>
                    <div id="toothTooltip{{ $entry->id }}" class="d-none">
                        <div class="tab-pane show active" id="visual" role="tabpanel" aria-labelledby="visual-tab">
                            <div class="tooth-chart mx-auto">
                                <x-teeth-visual :selectedTeeth="$entry->teeth_list" />
                            </div>
                        </div>
                    </div>
                </td>
                <td>{{ $entry->amount }}</td>
                <td>{{ $entry->unit_price }}</td>
                <td>{{ $entry->discount }}</td>
                <td>{{ $entry->price }}</td>
                <td>{{ $entry->cost }}</td>
                <td>
                    <!-- Button to delete the entry -->
                    <div class="text-end">
                        <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal"
                            id="{{ $entry->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#FFFFFF" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path
                                    d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th scope="row" colspan="8" class="text-md-center">Number of Entries: {{ $entries->count() }}</th>
            <td>Total: {{ $entries->sum('price') }}</td>
            <td>Total: {{ $entries->sum('cost') }}</td>
            <td>
                <!-- Button to delete all the entries displayed in the table -->
                <div class="text-end">
                    <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#FFFFFF" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path
                                d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
    </tfoot>
@endsection
@section('dropdown')
    <!-- This is the dropdown menu for the filter column search options in the entries table -->
    <li class="dropdown-item">All</li>
    <li class="dropdown-item">ID</li>
    <li class="dropdown-item" id="{{ Session::get('customer') ? 'customer_search' : '' }}">Name</li>
    <li class="dropdown-item" id="{{ Session::get('item') ? 'item_search' : '' }}">Item</li>
    <li class="dropdown-item">Amount</li>
    <li class="dropdown-item">Unit Price</li>
    <li class="dropdown-item">Discount</li>
    <li class="dropdown-item">Price</li>
    <li class="dropdown-item">Cost</li>
@endsection
@section('modal')
    <!-- This is the modal for deleting an entry -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="Delete">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Delete Selected Entry(s)?</h5>
                    <p class="mb-0">You can always change your mind. Are you Sure?</p>
                </div>
                <form class="modal-footer flex-nowrap p-0" action="{{ route('Entry.delete') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('delete')
                    <!-- Hidden inputs in case of deleting several entries -->
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
