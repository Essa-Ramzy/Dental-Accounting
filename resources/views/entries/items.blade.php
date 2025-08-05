@extends('layouts.table')
@section('head')
    @parent
    <script src="{{ asset('resources/js/views/items.js') }}"></script>
@endsection
@section('content')
    <!-- This is the layout for the items table -->
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Date</th>
        <th scope="col">Name</th>
        <th scope="col">Price</th>
        <th scope="col">Cost</th>
        <th scope="col">Description</th>
        <th scope="col" class="text-center">Records</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <th scope="row">{{ $item->id }}</th>
            <td>{{ $item->updated_at->format('d-m-Y') }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->cost }}</td>
            <td>{{ $item->description }}</td>
            <!-- View the records of the item in the entries table -->
            <td>
                <a href="{{ route('Item.records', ['id' => $item->id]) }}" type="button"
                   class="btn btn-sm btn-info col-8 offset-2">View</a>
            </td>
            <!-- Edit and delete the item -->
            <td>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('Item.edit', ['id' => $item->id]) }}" class="text-decoration-none">
                        <svg id="edit" width="20" height="20" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg"
                             class="icon-link-hover">
                            <path
                                d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z"
                                stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path
                                d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13"
                                stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal" id="{{ $item->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#FFFFFF" width="24" height="24"
                             viewBox="0 0 24 24">
                            <path
                                d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z"/>
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th scope="row" colspan="7" class="text-md-center">Number of Items: {{ $items->count() }}</th>
        <!-- Delete all the items displayed in the table -->
        <td>
            <div class="text-end">
                <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#FFFFFF" width="24" height="24"
                         viewBox="0 0 24 24">
                        <path
                            d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z"/>
                    </svg>
                </a>
            </div>
        </td>
    </tr>
    </tfoot>
@endsection
@section('dropdown')
    <!-- This is the dropdown menu for the filter column search options in the items table -->
    <li class="dropdown-item">All</li>
    <li class="dropdown-item">ID</li>
    <li class="dropdown-item">Name</li>
    <li class="dropdown-item">Price</li>
    <li class="dropdown-item">Cost</li>
@endsection
@section('modal')
    <!-- This is the modal for deleting an item -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="Delete" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Delete Selected Item(s)?</h5>
                    <p class="mb-0">You can always change your mind. All the entries related to this item will be
                        deleted as
                        well. Are you Sure?</p>
                </div>
                <form class="modal-footer flex-nowrap p-0" action="{{ route('Item.delete') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('delete')
                    <!-- Hidden inputs in case of deleting several items -->
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

