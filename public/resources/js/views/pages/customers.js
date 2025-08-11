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
                    Object.values(data.body)
                        .map((customer) => {
                            return `
                            <tr>
                                <th scope="row">${customer.id}</th>
                                <td>${customer.date}</td>
                                <td>${customer.name}</td>
                                <td>
                                    <a href="${customer.record_link}" type="button"
                                    class="btn btn-sm btn-info col-8 offset-2">View</a>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="${customer.edit_link}" class="text-decoration-none">
                                            <svg id="edit" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                                                class="icon-link-hover">
                                                <use href="#pencil-square" fill="none" stroke="var(--bs-body-color)"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></use>
                                            </svg>
                                        </a>
                                        <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal" id="${customer.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                                                <use href="#trash-fill" fill="var(--bs-body-color)"></use>
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
                            <th scope="row" colspan="4" class="text-md-center">Number of Customers: ${data.footer.count}</th>
                            <td>
                                <div class="text-end">
                                    <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                                            <use href="#trash-fill" fill="var(--bs-body-color)"></use>
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
