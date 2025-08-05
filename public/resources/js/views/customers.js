let handleDeleteModalClick = () => {
    $('a[href="#deleteModal"]').on("click", (e) => {
        let search = $("#search").val().toLowerCase();
        let filter = $("#dropdown_btn").text().toLowerCase().replace(" ", "_");
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

$(() => {
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
                $(".table-responsive tbody").html(data["body"]);
                $(".table-responsive tfoot").html(data["footer"]);
                handleDeleteModalClick();
            },
        });
    });
});