<tr>
    <th scope="row" colspan="4" class="text-center fw-semibold">
        Total Customers: {{ $footer['count'] }}
    </th>
    <td class="text-center">
        <div class="btn-group" role="group" aria-label="Customer actions">
            <a href="{{ route('Customer.create') }}" class="btn btn-sm btn-outline-success border-secondary border-end-0"
                aria-label="Add new customer">
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
