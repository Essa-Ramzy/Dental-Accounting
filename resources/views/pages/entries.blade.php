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
            <th scope="col" class="text-center">#</th>
            <th scope="col">Date</th>
            <th scope="col">Patient Name</th>
            <th scope="col">Treatment</th>
            <th scope="col" class="text-center">Teeth</th>
            <th scope="col" class="text-center">Qty</th>
            <th scope="col" class="text-end">Unit Price</th>
            <th scope="col" class="text-end">Discount</th>
            <th scope="col" class="text-end">Total</th>
            <th scope="col" class="text-end">Cost</th>
            <th scope="col" style="width: 120px;">
                <div class="dropdown text-end position-static">
                    <!-- Button to export the table data -->
                    <button type="button" class="btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false"
                        data-bs-auto-close="outside" id="export_btn" title="Export to PDF">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                            <use href="#file-pdf" fill="var(--bs-body-color)"></use>
                        </svg>
                    </button>
                    <!-- Export column options -->
                    <div class="dropdown-menu py-2 px-3">
                        <h6 class="dropdown-header text-center">Export Options</h6>
                        <form action="{{ route('Entry.export') }}" method="post" class="top-0"
                            enctype="multipart/form-data" target="_blank">
                            @csrf
                            <!-- Hidden inputs for exporting the filtered data -->
                            <input hidden aria-label="export_filter" name="filter">
                            <input hidden aria-label="export_search" name="search">
                            <input hidden aria-label="export_from_date" name="from_date">
                            <input hidden aria-label="export_to_date" name="to_date">
                            <!-- Export column options -->
                            <div class="form-check form-switch mb-1">
                                <input name="id" class="form-check-input" type="checkbox" role="switch" id="id"
                                    value="ID" checked>
                                <label class="form-check-label" for="id">ID</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input name="date" class="form-check-input" type="checkbox" role="switch" id="date"
                                    value="Date" checked>
                                <label class="form-check-label" for="date">Date</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input name="name" class="form-check-input" type="checkbox" role="switch" id="name"
                                    value="Name" checked>
                                <label class="form-check-label" for="name">Patient Name</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input name="item" class="form-check-input" type="checkbox" role="switch" id="item"
                                    value="Item" checked>
                                <label class="form-check-label" for="item">Treatment</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input name="teeth" class="form-check-input" type="checkbox" role="switch" id="teeth"
                                    value="Teeth" checked>
                                <label class="form-check-label" for="teeth">Teeth</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input name="amount" class="form-check-input" type="checkbox" role="switch" id="amount"
                                    value="Amount" checked>
                                <label class="form-check-label" for="amount">Quantity</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input name="unit_price" class="form-check-input" type="checkbox" role="switch"
                                    id="unit_price" value="Unit Price" checked>
                                <label class="form-check-label" for="unit_price">Unit Price</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input name="discount" class="form-check-input" type="checkbox" role="switch"
                                    id="discount" value="Discount" checked>
                                <label class="form-check-label" for="discount">Discount</label>
                            </div>
                            <div class="form-check form-switch mb-1">
                                <input name="price" class="form-check-input" type="checkbox" role="switch"
                                    id="price" value="Price" checked>
                                <label class="form-check-label" for="price">Total Price</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input name="cost" class="form-check-input" type="checkbox" role="switch"
                                    id="cost" value="Cost" checked>
                                <label class="form-check-label" for="cost">Cost</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <svg width="16" height="16" class="me-2 mb-1" aria-hidden="true">
                                        <use href="#download" fill="currentColor" />
                                    </svg>
                                    Export PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        @forelse ($entries as $entry)
            <tr class="align-middle">
                <th scope="row" class="text-center text-muted">{{ $entry->id }}</th>
                <td class="text-muted">
                    {{ $entry->date->format('M d, Y') }}
                </td>
                <td class="fw-semibold">
                    {{ $entry->customer->name }}
                </td>
                <td class="fw-medium">
                    {{ $entry->item->name }}
                </td>
                <td class="text-center">
                    <span role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                        data-bs-content-target="toothTooltip{{ $entry->id }}"
                        class="badge bg-light-subtle text-body border">
                        {{ $entry->teeth }}
                        <svg width="12" height="12" class="ms-1 opacity-75" aria-hidden="true">
                            <use href="#eye" fill="currentColor" />
                        </svg>
                    </span>
                    <div id="toothTooltip{{ $entry->id }}" class="d-none">
                        <div class="text-center p-2">
                            <div class="fw-semibold mb-2">Treated Teeth</div>
                            <div class="tooth-chart mx-auto">
                                <x-teeth-visual :selectedTeeth="$entry->teeth_list" />
                            </div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge bg-primary rounded-pill">{{ $entry->amount }}</span>
                </td>
                <td class="text-end fw-semibold text-nowrap">£
                    {{ number_format($entry->unit_price, strlen(rtrim(substr(strrchr($entry->unit_price, '.'), 1), '0'))) }}
                </td>
                <td class="text-end {{ $entry->discount > 0 ? 'text-danger' : 'text-muted' }} text-nowrap">
                    {{ $entry->discount > 0 ? '-£ ' . number_format($entry->discount, strlen(rtrim(substr(strrchr($entry->discount, '.'), 1), '0'))) : '£ 0' }}
                </td>
                <td class="text-end fw-bold text-success text-nowrap">£
                    {{ number_format($entry->price, strlen(rtrim(substr(strrchr($entry->price, '.'), 1), '0'))) }}
                </td>
                <td class="text-end text-muted text-nowrap">£
                    {{ number_format($entry->cost, strlen(rtrim(substr(strrchr($entry->cost, '.'), 1), '0'))) }}
                </td>
                <td class="text-end">
                    <div class="btn-group" role="group" aria-label="Entry actions">
                        <a href="{{ route('Entry.edit', ['id' => $entry->id]) }}"
                            class="btn btn-sm btn-outline-primary border-secondary border-end-0"
                            aria-label="Edit entry for {{ $entry->customer->name }}">
                            <svg width="20" height="20" aria-hidden="true">
                                <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <a href="#deleteModal" data-bs-toggle="modal" id="{{ $entry->id }}"
                            class="btn btn-sm btn-outline-danger border-secondary border-start-0"
                            aria-label="Delete entry for {{ $entry->customer->name }}">
                            <svg width="24" height="24" aria-hidden="true">
                                <use href="#trash-fill" fill="currentColor" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="12" class="text-center py-5 text-muted">
                    <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                        <use href="#journal-medical" fill="currentColor" />
                    </svg>
                    <div class="h5">No entries found</div>
                    <p class="mb-3">Start by adding your first treatment entry.</p>
                    <a href="{{ route('Entry.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" class="me-1 mb-1" aria-hidden="true">
                            <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Add Entry
                    </a>
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th scope="row" colspan="8" class="text-center fw-semibold">
                Total Entries: {{ $entries->count() }}
            </th>
            <td class="text-end fw-bold text-success text-nowrap">
                £
                {{ number_format($entries->sum('price'), strlen(rtrim(substr(strrchr($entries->sum('price'), '.'), 1), '0'))) }}
            </td>
            <td class="text-end fw-semibold text-muted text-nowrap">
                £
                {{ number_format($entries->sum('cost'), strlen(rtrim(substr(strrchr($entries->sum('cost'), '.'), 1), '0'))) }}
            </td>
            <td class="text-end">
                <div class="btn-group" role="group" aria-label="Bulk actions">
                    <a href="{{ route('Entry.create') }}"
                        class="btn btn-sm btn-outline-success border-secondary border-end-0" aria-label="Add new entry">
                        <svg width="20" height="20" aria-hidden="true">
                            <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <a class="btn btn-sm btn-outline-danger border-secondary border-start-0" data-bs-toggle="modal"
                        href="#deleteModal" aria-label="Delete selected entries">
                        <svg width="24" height="24" aria-hidden="true">
                            <use href="#trash-fill" fill="currentColor"></use>
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
    </tfoot>
@endsection
@section('dropdown')
    <!-- This is the dropdown menu for the filter column search options in the entries table -->
    <li><button class="dropdown-item" type="button">All</button></li>
    <li><button class="dropdown-item" type="button">ID</button></li>
    <li><button class="dropdown-item" type="button"
            id="{{ Session::get('customer') ? 'customer_search' : '' }}">Patient Name</button></li>
    <li><button class="dropdown-item" type="button"
            id="{{ Session::get('item') ? 'item_search' : '' }}">Treatment</button></li>
    <li><button class="dropdown-item" type="button">Quantity</button></li>
    <li><button class="dropdown-item" type="button">Unit Price</button></li>
    <li><button class="dropdown-item" type="button">Discount</button></li>
    <li><button class="dropdown-item" type="button">Total Price</button></li>
    <li><button class="dropdown-item" type="button">Cost</button></li>
@endsection
@section('modal')
    <!-- This is the modal for deleting an entry -->
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
                    <p class="mb-0">Are you sure you want to delete this/these entry(s)? This action cannot be undone.
                    </p>
                </div>
                <form action="{{ route('Entry.delete') }}" method="post" enctype="multipart/form-data"
                    class="d-flex modal-footer p-0">
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
