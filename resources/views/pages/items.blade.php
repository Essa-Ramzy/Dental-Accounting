@extends('layouts.table')
@section('head')
    @parent
    <script src="{{ asset('resources/js/views/pages/items.js') }}"></script>
@endsection
@section('content')
    <!-- This is the layout for the items table -->
    <thead>
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col">Date Added</th>
            <th scope="col">Item Name</th>
            <th scope="col" class="text-end">Price</th>
            <th scope="col" class="text-end">Cost</th>
            <th scope="col">Description</th>
            <th scope="col" class="text-center">Records</th>
            <th scope="col" class="text-center" style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($items as $item)
            <tr class="align-middle">
                <th scope="row" class="text-center text-muted">{{ $item->id }}</th>
                <td class="text-muted">
                    {{ $item->updated_at->format('M d, Y') }}
                </td>
                <td>
                    <div class="fw-semibold">{{ $item->name }}</div>
                </td>
                <td class="text-end">
                    <span class="fw-semibold text-success">£
                        {{ number_format($item->price, strlen(rtrim(substr(strrchr($item->price, '.'), 1), '0'))) }}</span>
                </td>
                <td class="text-end">
                    <span class="text-muted">£
                        {{ number_format($item->cost, strlen(rtrim(substr(strrchr($item->cost, '.'), 1), '0'))) }}</span>
                </td>
                <td>
                    <span class="text-truncate d-inline-block" style="max-width: 20rem;" title="{{ $item->description }}"
                        data-bs-toggle="tooltip">
                        {{ $item->description == 'N/A' ? 'No description' : $item->description }}
                    </span>
                </td>
                <td class="text-center">
                    <a href="{{ route('Item.records', ['id' => $item->id]) }}" class="btn btn-sm btn-outline-primary">
                        <span class="badge bg-primary rounded-pill">{{ $item->entries->count() }}</span>
                        View Records
                    </a>
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group" aria-label="Item actions">
                        <a href="{{ route('Item.edit', ['id' => $item->id]) }}"
                            class="btn btn-sm btn-outline-primary border-secondary border-end-0"
                            aria-label="Edit {{ $item->name }}">
                            <svg width="20" height="20" aria-hidden="true">
                                <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <a href="#deleteModal" data-bs-toggle="modal" id="{{ $item->id }}"
                                class="btn btn-sm btn-outline-danger border-secondary border-start-0"
                                aria-label="Delete {{ $item->name }}">
                                <svg width="24" height="24" aria-hidden="true">
                                    <use href="#trash-fill" fill="currentColor" />
                                </svg>
                            </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                        <use href="#tags" fill="currentColor" />
                    </svg>
                    <div class="h5">No items found</div>
                    <p class="mb-3">Get started by adding your first treatment item.</p>
                    <a href="{{ route('Item.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" class="me-1 mb-1" aria-hidden="true">
                            <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Add Item
                    </a>
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th scope="row" colspan="7" class="text-center fw-semibold">
                Total Items: {{ $items->count() }}
            </th>
            <td class="text-center">
                <div class="btn-group" role="group" aria-label="Item actions">
                    <a href="{{ route('Item.create') }}"
                        class="btn btn-sm btn-outline-success border-secondary border-end-0" aria-label="Add new item">
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
    <!-- This is the dropdown menu for the filter column search options in the items table -->
    <li><button class="dropdown-item" type="button">All</button></li>
    <li><button class="dropdown-item" type="button">ID</button></li>
    <li><button class="dropdown-item" type="button">Name</button></li>
    <li><button class="dropdown-item" type="button">Price</button></li>
    <li><button class="dropdown-item" type="button">Cost</button></li>
@endsection
@section('modal')
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
