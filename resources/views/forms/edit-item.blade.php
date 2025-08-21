@extends('layouts.form')
@section('svg-icons')
    @parent
    <symbol id="pencil-square" viewBox="0 0 24 24">
        <path
            d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z">
        </path>
        <path
            d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13">
        </path>
    </symbol>
@endsection
@section('content')
    <!-- This is the layout for editing an item -->
    <form action="{{ route('Item.update', ['id' => $item->id]) }}" method="post" enctype="multipart/form-data"
        class="needs-validation" novalidate>
        @csrf
        @method('PATCH')
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('Item.index') }}"
                        class="btn btn-outline-secondary me-3 d-flex align-items-center gap-2"
                        aria-label="Go back to items">
                        <svg width="16" height="16" aria-hidden="true">
                            <use href="#arrow-left" fill="currentColor" />
                        </svg>
                        Back
                    </a>
                    <h1 class="h3 mb-0 fw-bold">Edit Item</h1>
                </div>

                <!-- Item Information Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h2 class="h6 mb-0 d-flex align-items-center gap-2">
                            <svg width="18" height="18" aria-hidden="true">
                                <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Update Item Information
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Item Name -->
                            <div class="col-12 mb-3">
                                <label for="name" class="form-label fw-semibold">Item Name <span
                                        class="text-danger">*</span></label>
                                <input id="name" type="text"
                                    class="form-control form-control-lg{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    name="name" value="{{ old('name', $item->name) }}" autocomplete="off" autofocus
                                    required placeholder="Enter treatment or service name">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                                <div class="form-text">
                                    Current name: <strong>{{ $item->name }}</strong>
                                </div>
                            </div>
                            <!-- Price and Cost -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-semibold">Price <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative{{ $errors->has('price') ? ' is-invalid' : '' }}">
                                    <span
                                        class="input-group-text position-absolute top-50 start-0 translate-middle-y border-0 rounded-end-0 border-end"
                                        style="margin-left: calc(1 * var(--bs-border-width));">£</span>
                                    <input id="price" type="number" step="0.01" min="0"
                                        class="form-control ps-5{{ $errors->has('price') ? ' is-invalid' : '' }}"
                                        name="price" value="{{ old('price', $item->price) }}" required placeholder="0.00">
                                </div>
                                @if ($errors->has('price'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </div>
                                @endif
                                <div class="form-text">Current:
                                    £
                                    {{ number_format($item->price, strlen(rtrim(substr(strrchr($item->price, '.'), 1), '0'))) }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cost" class="form-label fw-semibold">Cost <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative{{ $errors->has('cost') ? ' is-invalid' : '' }}">
                                    <span
                                        class="input-group-text position-absolute top-50 start-0 translate-middle-y border-0 rounded-end-0 border-end"
                                        style="margin-left: calc(1 * var(--bs-border-width));">£</span>
                                    <input id="cost" type="number" step="0.01" min="0"
                                        class="form-control ps-5{{ $errors->has('cost') ? ' is-invalid' : '' }}"
                                        name="cost" value="{{ old('cost', $item->cost) }}" required placeholder="0.00">
                                </div>
                                @if ($errors->has('cost'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('cost') }}</strong>
                                    </div>
                                @endif
                                <div class="form-text">Current:
                                    £
                                    {{ number_format($item->cost, strlen(rtrim(substr(strrchr($item->cost, '.'), 1), '0'))) }}
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                    name="description" rows="4" placeholder="Enter detailed description of the treatment or service (optional)">{{ old('description', $item->description) }}</textarea>
                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </div>
                                @endif
                                <div class="form-text">Provide additional details about this item (optional)</div>
                            </div>

                            <!-- Item Metadata -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Item ID</label>
                                <div
                                    class="form-control-plaintext bg-secondary-subtle rounded p-2 border border-secondary-subtle">
                                    <strong>#{{ $item->id }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Created Date</label>
                                <div
                                    class="form-control-plaintext bg-secondary-subtle rounded p-2 border border-secondary-subtle">
                                    {{ $item->created_at->format('F j, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('Item.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning px-4 d-flex align-items-center gap-2">
                        <svg width="16" height="16" aria-hidden="true">
                            <use href="#check-lg" fill="currentColor" />
                        </svg>
                        Update Item
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
