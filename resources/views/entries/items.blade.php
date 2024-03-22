@extends('layouts.table')

@section('content')
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
    @foreach($entries as $entry)
        <tr>
            <th scope="row">{{ $entry->id }}</th>
            <td>{{ $entry->updated_at->format('d-m-Y') }}</td>
            <td>{{ $entry->name }}</td>
            <td>{{ $entry->price }}</td>
            <td>{{ $entry->cost }}</td>
            <td>{{ $entry->description }}</td>
            <td>
                <a href="{{ route('Item.records', ['id' => $entry->id]) }}" type="button"
                   class="btn btn-sm btn-info col-8 offset-2">View</a>
            </td>
            <td>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('Item.edit', ['id' => $entry->id]) }}" class="text-decoration-none">
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
                    <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal" id="{{ $entry->id }}">
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
        <th scope="row" colspan="8" class="text-md-center">Number of Items: {{ $entries->count() }}</th>
    </tr>
@endsection

@section('dropdown')
    <li class="dropdown-item">All</li>
    <li class="dropdown-item">ID</li>
    <li class="dropdown-item">Name</li>
    <li class="dropdown-item">Price</li>
    <li class="dropdown-item">Cost</li>
@endsection

@section('modal')
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="Delete" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Delete This Item?</h5>
                    <p class="mb-0">You can always change your mind. All the entries related to this item will be
                        deleted as
                        well. Are you Sure?</p>
                </div>
                <div class="modal-footer flex-nowrap p-0">
                    <a type="button"
                       class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end">
                        <strong>Yes, Delete</strong></a>
                    <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0"
                            data-bs-dismiss="modal">No thanks
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

