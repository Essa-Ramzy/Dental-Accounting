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
                            .map((item) => {
                                return `
                                    <tr class="align-middle">
                                        <th scope="row" class="text-center text-muted">${
                                            item.id
                                        }</th>
                                        <td class="text-muted">
                                            ${item.date}
                                        </td>
                                        <td>
                                            <div class="fw-semibold">${
                                                item.name
                                            }</div>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-semibold text-success">£
                                                ${Number(
                                                    item.price
                                                ).toLocaleString("en-US", {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 2,
                                                })}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-muted">£
                                                ${Number(
                                                    item.cost
                                                ).toLocaleString("en-US", {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 2,
                                                })}</span>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 20rem;" title="${
                                                item.description
                                            }"
                                                data-bs-toggle="tooltip">
                                                ${
                                                    item.description == "N/A"
                                                        ? "No description"
                                                        : item.description
                                                }
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="${
                                                item.record_link
                                            }" class="btn btn-sm btn-outline-primary">
                                                <span class="badge bg-primary rounded-pill">${
                                                    item.entries_count
                                                }</span>
                                                View Records
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group" aria-label="Item actions">
                                                <a href="${item.edit_link}"
                                                    class="btn btn-sm btn-outline-primary border-secondary border-end-0"
                                                    aria-label="Edit ${
                                                        item.name
                                                    }">
                                                    <svg width="20" height="20" aria-hidden="true">
                                                        <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <a href="#deleteModal" data-bs-toggle="modal" id="${
                                                        item.id
                                                    }"
                                                        class="btn btn-sm btn-outline-danger border-secondary border-start-0"
                                                        aria-label="Delete ${
                                                            item.name
                                                        }">
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
                            <td colspan="8" class="text-center py-5 text-muted">
                                <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                                    <use href="#tags" fill="currentColor" />
                                </svg>
                                <div class="h5">No items found</div>
                                <p class="mb-3">Get started by adding your first treatment item.</p>
                                <a href="${data.footer.create_link}" class="btn btn-primary">
                                    Add Item
                                </a>
                            </td>
                        </tr>`
                );
                $(".table-responsive tfoot").html(`
                        <tr>
                            <th scope="row" colspan="7" class="text-center fw-semibold">
                                Total Items: ${data.footer.count}
                            </th>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Item actions">
                                    <a href="${data.footer.create_link}"
                                        class="btn btn-sm btn-outline-success border-secondary border-end-0" aria-label="Add new item">
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
