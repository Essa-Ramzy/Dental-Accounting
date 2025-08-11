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
                        .map((item) => {
                            return `
                            <tr>
                                <th scope="row">${item.id}</th>
                                <td>${item.date}</td>
                                <td>${item.name}</td>
                                <td>${item.price}</td>
                                <td>${item.cost}</td>
                                <td>${item.description}</td>
                                <td>
                                    <a href=" . route('Item.records', ['id' => ${item.id}]) . " type="button"
                                    class="btn btn-sm btn-info col-8 offset-2">View</a>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href=" . route('Item.edit', ['id' => ${item.id}]) . " class="text-decoration-none">
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
                                        <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal" id="${item.id}">
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
                            <th scope="row" colspan="7" class="text-md-center">Number of Items: ${data.footer.count}</th>
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
            },
        });
    });
});
