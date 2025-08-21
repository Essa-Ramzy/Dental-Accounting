@extends('layouts.table')
@section('content')
    <!-- This is the layout for the customers table -->
    <thead>
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col">{{ $trash ? 'Deleted At' : 'Date' }}</th>
            <th scope="col" class="">Customer Name</th>
            @if (!$trash)
                <th scope="col" class="text-center">Records</th>
            @endif
            <th scope="col" class="text-center" style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @include('partials.customers-body', compact('customers'))
    </tbody>
    <tfoot>
        @include('partials.customers-footer', compact('customers'))
    </tfoot>
@endsection
@section('dropdown')
    <!-- This is the dropdown menu for the filter column search in the customers table -->
    <li><button class="dropdown-item" type="button">All</button></li>
    <li><button class="dropdown-item" type="button">ID</button></li>
    <li><button class="dropdown-item" type="button">Name</button></li>
@endsection
@section('modal')
    @if ($trash)
        <!-- This is the modal for restoring an item -->
        <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-success d-flex align-items-center gap-2" id="restoreModalLabel">
                            <svg width="24" height="24" aria-hidden="true">
                                <use href="#arrow-clockwise" fill="currentColor" />
                            </svg>
                            Confirm Restoration
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <p class="mb-0">
                            Are you sure you want to restore the selected customer(s)? Restored customers will be moved back
                            to the active list. If a customer already exists in the active list, the restore will not be
                            successful.
                        </p>
                    </div>
                    <form action="{{ route('Customer.restore') }}" method="post" enctype="multipart/form-data"
                        class="d-flex modal-footer p-0">
                        @csrf
                        @method('patch')
                        <!-- Hidden input in case of restoring several items -->
                        <input hidden aria-label="restore_filter" name="filter">
                        <input hidden aria-label="restore_search" name="search">
                        <input hidden aria-label="restore_from_date" name="from_date">
                        <input hidden aria-label="restore_to_date" name="to_date">
                        <button type="submit"
                            class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end">
                            <strong>Yes, Restore</strong></button>
                        <button type="button"
                            class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0"
                            data-bs-dismiss="modal">No thanks
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Failed Restore Modal -->
    @if (session('failed_customers') && count(session('failed_customers')) > 0)
        <div class="modal fade" id="failedModal" tabindex="-1" aria-labelledby="failedModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-warning d-flex align-items-center gap-2" id="failedModalLabel">
                            <svg width="24" height="24" aria-hidden="true">
                                <use href="#exclamation-triangle" fill="currentColor" />
                            </svg>
                            Restore Failed
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-0">
                        <p class="mb-2">The following customers could not be restored:</p>
                        <div class="overflow-y-auto" style="max-height: 50vh;">
                            <ul class="list-group list-group-flush" id="failedCustomersList">
                                @php
                                    $failedCustomers = session('failed_customers');
                                    $visibleCount = 3;
                                    $totalCount = count($failedCustomers);
                                    $hiddenCount = $totalCount - $visibleCount;
                                @endphp
                                @foreach ($failedCustomers as $index => $failed)
                                    <li
                                        class="list-group-item px-0 py-1 border-0 @if ($index >= $visibleCount) d-none failed-item-hidden @endif">
                                        <div class="d-flex align-items-center gap-2">
                                            <svg width="16" height="16" class="text-danger" aria-hidden="true">
                                                <use href="#x-circle" fill="currentColor" />
                                            </svg>
                                            <span class="text-muted">{{ $failed }}</span>
                                        </div>
                                    </li>
                                @endforeach
                                @if ($hiddenCount > 0)
                                    <li class="list-group-item px-0 py-2 border-0 text-center" id="showMore">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill">
                                            <svg width="14" height="14" aria-hidden="true">
                                                <use href="#chevron-down" fill="currentColor" />
                                            </svg>
                                            <small class="fw-medium">Show {{ $hiddenCount }} more
                                                customer{{ $hiddenCount > 1 ? 's' : '' }}</small>
                                        </button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- This is the delete modal for the customers table -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger d-flex align-items-center gap-2" id="deleteModalLabel">
                        <svg width="20" height="20" aria-hidden="true">
                            <use href="#trash-fill" fill="currentColor" />
                        </svg>
                        Confirm {{ $trash ? 'Permanent ' : '' }}Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="mb-0">
                        {{ $trash ? 'Are you sure you want to permanently delete the selected customer(s)? This action cannot be undone and will also remove all related entries.' : 'Are you sure you want to delete the selected customer(s)? This action cannot be undone.' }}
                    </p>
                </div>
                <form action="{{ $trash ? route('Customer.forceDelete') : route('Customer.delete') }}" method="post"
                    enctype="multipart/form-data" class="d-flex modal-footer p-0">
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
