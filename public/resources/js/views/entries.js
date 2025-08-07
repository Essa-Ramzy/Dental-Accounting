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
                    data.body
                        .map((entry) => {
                            return `
                            <tr>
                                <th scope="row">${entry.id}</th>
                                <td>${entry.date}</td>
                                <td>${entry.customer_name}</td>
                                <td>${entry.item_name}</td>
                                <td>${entry.teeth}</td>
                                <td>${entry.amount}</td>
                                <td>${entry.unit_price}</td>
                                <td>${entry.discount}</td>
                                <td>${entry.price}</td>
                                <td>${entry.cost}</td>
                                <td>
                                    <div class="text-end">
                                        <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal" id="${entry.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#FFFFFF" width="24" height="24"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>`;
                        })
                        .join("")
                );
                $(".table-responsive tfoot").html(`
                        <tr>
                            <th scope="row" colspan="8" class="text-md-center">Number of Entries: ${data.footer.count}</th>
                            <td>Total: ${data.footer.total_price}</td>
                            <td>Total: ${data.footer.total_cost}</td>
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
