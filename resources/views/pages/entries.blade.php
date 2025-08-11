@extends('layouts.table')
@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('resources/css/views/components/visual-teeth.css') }}">
    <script src="{{ asset('resources/js/views/pages/entries.js') }}"></script>
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
                <div class="dropdown text-end position-static">
                    <!-- Button to export the table data -->
                    <button type="button" class="btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false"
                        data-bs-auto-close="outside" id="export_btn">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                            <use href="#file-pdf" fill="var(--bs-body-color)"></use>
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
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('Entry.edit', ['id' => $entry->id]) }}" class="text-decoration-none">
                            <svg id="edit" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                                class="icon-link-hover">
                                <use href="#pencil-square" fill="none" stroke="var(--bs-body-color)" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></use>
                            </svg>
                        </a>
                        <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal"
                            id="{{ $entry->id }}">
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
            <th scope="row" colspan="8" class="text-md-center">Number of Entries: {{ $entries->count() }}</th>
            <td>Total: {{ $entries->sum('price') }}</td>
            <td>Total: {{ $entries->sum('cost') }}</td>
            <td>
                <!-- Button to delete all the entries displayed in the table -->
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
