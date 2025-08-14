$(() => {
    let handleDeleteModalClick = () => {
        $('a[href="#deleteModal"]').on("click", (e) => {
            let search = $("#search").val().toLowerCase();
            let filter = $("#dropdown_btn")
                .text()
                .toLowerCase()
                .replace(" ", "_");
            if (e.currentTarget.id) {
                search = e.currentTarget.id;
                filter = "id";
            }
            let from_date = $("#from_date").val();
            let to_date = $("#to_date").val();

            $('input[aria-label="delete_filter"]').val(
                !filter.includes("search_by") ? filter : ""
            );
            $('input[aria-label="delete_search"]').val(
                !filter.includes("search_by") && search ? search : ""
            );
            $('input[aria-label="delete_from_date"]').val(
                from_date ? from_date + " 00:00:00" : ""
            );
            $('input[aria-label="delete_to_date"]').val(
                to_date ? to_date + " 23:59:59" : ""
            );
        });
    };
    handleDeleteModalClick();

    $("#search").on("change", () => {
        let search = $("#search").val().toLowerCase();
        let filter = $("#dropdown_btn").text().toLowerCase().replace(" ", "_");
        let from_date = $("#from_date").val();
        let to_date = $("#to_date").val();
        $.ajax({
            url: document
                .querySelector('meta[name="search-url"]')
                .getAttribute("content"),
            type: "GET",
            data: {
                search: !filter.includes("search_by") && search ? search : "",
                filter: !filter.includes("search_by") ? filter : "",
                from_date: from_date ? from_date + " 00:00:00" : "",
                to_date: to_date ? to_date + " 23:59:59" : "",
            },
            success: function (data) {
                $(".table-responsive tbody").html(
                    (rows = Object.values(data.body)).length
                        ? rows
                            .map((customer) => {
                                return `
                                <tr class="align-middle">
                                    <th scope="row" class="text-center text-muted">${customer.id}</th>
                                    <td class="text-muted">
                                        ${customer.date}
                                    </td>
                                    <td>
                                        <div class="fw-semibold">${customer.name}</div>
                                    </td>
                                    <td class="text-center">
                                        <a href="${customer.record_link}"
                                            class="btn btn-sm btn-outline-primary">
                                            <span class="badge bg-primary rounded-pill me-1">${customer.entries_count}</span>
                                            View Records
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Customer actions">
                                            <a href="${customer.edit_link}"
                                                class="btn btn-sm btn-outline-primary border-secondary border-end-0"
                                                aria-label="Edit ${customer.name}">
                                                <svg width="20" height="20" aria-hidden="true">
                                                    <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                            <a href="#deleteModal" data-bs-toggle="modal" id="${customer.id}"
                                                class="btn btn-sm btn-outline-danger border-secondary border-start-0"
                                                aria-label="Delete ${customer.name}">
                                                <svg width="24" height="24" aria-hidden="true">
                                                    <use href="#trash-fill" fill="currentColor" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>`;
                            })
                            .join("")
                        : `<tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                                    <use href="#people-circle" fill="currentColor" />
                                </svg>
                                <div class="h5">No customers found</div>
                                <p class="mb-3">Get started by adding your first customer.</p>
                                <a href="${data.footer.create_link}" class="btn btn-primary">
                                    Add Customer
                                </a>
                            </td>
                        </tr>`
                );
                $(".table-responsive tfoot").html(`
                        <tr>
                            <th scope="row" colspan="4" class="text-center fw-semibold">
                                Total Customers: ${data.footer.count}
                            </th>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Customer actions">
                                    <a href="${data.footer.create_link}"
                                        class="btn btn-sm btn-outline-success border-secondary border-end-0" aria-label="Add new customer">
                                        <svg width="16" height="16" aria-hidden="true">
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
                        </tr>`);
                handleDeleteModalClick();
            },
        });
    });
});
