@forelse ($items as $item)
    <tr class="align-middle">
        <th scope="row" class="text-center text-muted">{{ $item->id }}</th>
        <td class="text-muted">
            @if ($trash)
                {{ $item->deleted_at->format('M d, Y H:i') }}
            @else
                {{ $item->updated_at->format('M d, Y') }}
            @endif
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
        <td class="text-center">
            @if ($item->description)
                <span role="button" data-bs-toggle="modal" data-bs-target="#descriptionModal"
                    data-item-name="{{ $item->name }}" data-description="{{ $item->description }}"
                    class="badge bg-light-subtle text-body border d-inline-flex align-items-center gap-2">
                    {{ Str::limit($item->description, 50) }}
                    <svg width="12" height="12" class="opacity-75" aria-hidden="true">
                        <use href="#eye" fill="currentColor" />
                    </svg>
                </span>
            @else
                <span class="text-muted fst-italic">No description</span>
            @endif
        </td>
        @if (!$trash)
            <td class="text-center">
                <a href="{{ route('Item.records', ['id' => $item->id]) }}" class="btn btn-sm btn-outline-primary">
                    <span class="badge bg-primary rounded-pill">{{ $item->entries_count }}</span>
                    View Records
                </a>
            </td>
        @endif
        <td class="text-center">
            <div class="btn-group" role="group" aria-label="Item actions">
                @if ($trash)
                    <a href="#restoreModal" data-bs-toggle="modal" id="{{ $item->id }}"
                        class="btn btn-sm btn-outline-success border-secondary border-end-0 d-flex align-items-center d-flex align-items-center"
                        aria-label="Restore {{ $item->name }}">
                        <svg width="20" height="20" aria-hidden="true">
                            <use href="#arrow-clockwise" fill="currentColor" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('Item.edit', ['id' => $item->id]) }}"
                        class="btn btn-sm btn-outline-primary border-secondary border-end-0 d-flex align-items-center"
                        aria-label="Edit {{ $item->name }}">
                        <svg width="20" height="20" aria-hidden="true">
                            <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                @endif
                <a href="#deleteModal" data-bs-toggle="modal" id="{{ $item->id }}"
                    class="btn btn-sm btn-outline-danger border-secondary border-start-0 d-flex align-items-center"
                    aria-label="Delete {{ $item->name }}">
                    <svg width="20" height="20" aria-hidden="true">
                        <use href="#trash-fill" fill="currentColor" />
                    </svg>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="{{ $trash ? 7 : 8 }}" class="text-center py-5 text-muted">
            <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                <use href="#tags" fill="currentColor" />
            </svg>
            <div class="h5">No items found</div>
            @if ($trash)
                <p class="mb-3">Your trash is empty.</p>
                <a href="{{ route('Item.index') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <svg width="16" height="16" aria-hidden="true">
                        <use href="#box-arrow-in-right" fill="currentColor" />
                    </svg>
                    Go to Items
                </a>
            @else
                <p class="mb-3">Get started by adding your first treatment item.</p>
                <a href="{{ route('Item.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <svg width="16" height="16" aria-hidden="true">
                        <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Add Item
                </a>
            @endif
        </td>
    </tr>
@endforelse
