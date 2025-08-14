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

    let initTeethPopovers = () => {
        $('[data-bs-toggle="popover"]').each((_, e) => {
            const el = $(e);
            const targetId = el.attr("data-bs-content-target");
            const content = $("#" + targetId);

            new bootstrap.Popover(el[0], {
                html: true,
                container: "body",
                content: content.html(),
                sanitize: false,
                customClass: "teeth-popover",
            });
        });
    };
    initTeethPopovers();
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
                    (rows = Object.values(data.body))
                        ? rows
                            .map((entry) => {
                                return `
                                <tr class="align-middle">
                                    <th scope="row" class="text-center text-muted">${
                                        entry.id
                                    }</th>
                                    <td class="text-muted">
                                        ${entry.date}
                                    </td>
                                    <td>
                                        <div class="fw-semibold">${
                                            entry.customer_name
                                        }</div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">${
                                            entry.item_name
                                        }</div>
                                    </td>
                                    <td class="text-center">
                                        <span role="button" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true"
                                            data-bs-content-target="toothTooltip${
                                                entry.id
                                            }"
                                            class="badge bg-light-subtle text-body border position-relative">
                                            ${entry.teeth}
                                            <svg width="12" height="12" class="ms-1 opacity-75" aria-hidden="true">
                                                <use href="#eye" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <div id="toothTooltip${
                                            entry.id
                                        }" class="d-none">
                                            <div class="text-center p-2">
                                                <div class="fw-semibold mb-2">Treated Teeth</div>
                                                <div class="tooth-chart mx-auto">
                                                    <x-teeth-visual :selectedTeeth="${
                                                        entry.teeth_list
                                                    }" />
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">${
                                            entry.amount
                                        }</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold text-nowrap">£
                                            ${Number(
                                                entry.unit_price
                                            ).toLocaleString("en-US", {
                                                minimumFractionDigits: 0,
                                                maximumFractionDigits: 2,
                                            })}</span>
                                    </td>
                                    <td class="text-end">
                                        ${
                                            entry.discount > 0
                                                ? '<span class="text-danger text-nowrap">-£ ' +
                                                Number(
                                                    entry.discount
                                                ).toLocaleString("en-US", {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 2,
                                                }) +
                                                "</span>"
                                                : '<span class="text-muted text-nowrap">£ 0</span>'
                                        }
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-success text-nowrap">£
                                            ${Number(
                                                entry.price
                                            ).toLocaleString("en-US", {
                                                minimumFractionDigits: 0,
                                                maximumFractionDigits: 2,
                                            })}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-muted text-nowrap">£
                                            ${Number(entry.cost).toLocaleString(
                                                "en-US",
                                                {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 2,
                                                }
                                            )}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group" aria-label="Entry actions">
                                            <a href="${entry.edit_link}"
                                                class="btn btn-sm btn-outline-primary border-secondary border-end-0"
                                                aria-label="Edit entry for ${
                                                    entry.customer_name
                                                }">
                                                <svg width="20" height="20" aria-hidden="true">
                                                    <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                            <a href="#deleteModal" data-bs-toggle="modal" id="${
                                                entry.id
                                            }"
                                                class="btn btn-sm btn-outline-danger border-secondary border-start-0"
                                                aria-label="Delete entry for ${
                                                    entry.customer_name
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
                            <td colspan="12" class="text-center py-5 text-muted">
                                <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                                    <use href="#journal-medical" fill="currentColor" />
                                </svg>
                                <div class="h5">No entries found</div>
                                <p class="mb-3">Start by adding your first treatment entry.</p>
                                <a href="${data.footer.create_link}" class="btn btn-primary">
                                    <svg width="16" height="16" class="me-1 mb-1" aria-hidden="true">
                                        <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Add Entry
                                </a>
                            </td>
                        </tr>`
                );
                $(".table-responsive tfoot").html(`
                        <tr>
                            <th scope="row" colspan="8" class="text-center fw-semibold">
                                Total Entries: ${data.footer.count}
                            </th>
                            <td class="text-end fw-bold text-success text-nowrap">
                                £
                                ${Number(
                                    data.footer.total_price
                                ).toLocaleString("en-US", {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 2,
                                })}
                            </td>
                            <td class="text-end fw-semibold text-muted text-nowrap">
                                £
                                ${Number(data.footer.total_cost).toLocaleString(
                                    "en-US",
                                    {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 2,
                                    }
                                )}
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group" aria-label="Bulk actions">
                                    <a href="${data.footer.create_link}"
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
                        </tr>`);
                handleDeleteModalClick();
                initTeethPopovers();
            },
        });
    });

    $("#customer_search")?.click();
    $("#item_search")?.click();

    $("#export_btn").on("click", () => {
        let search = $("#search").val().toLowerCase();
        let filter = $("#dropdown_btn").text().toLowerCase().replace(" ", "_");
        let from_date = $("#from_date").val();
        let to_date = $("#to_date").val();

        $('input[aria-label="export_filter"]').val(
            !filter.includes("search_by") ? filter : ""
        );
        $('input[aria-label="export_search"]').val(
            !filter.includes("search_by") && search ? search : ""
        );
        $('input[aria-label="export_from_date"]').val(
            from_date ? from_date + " 00:00:00" : ""
        );
        $('input[aria-label="export_to_date"]').val(
            to_date ? to_date + " 23:59:59" : ""
        );
    });
});
