$(() => {
    $("#customer_search")?.click();
    $("#item_search")?.click();

    $("#export_btn").on("click", () => {
        let search = $("#search").val().toLowerCase();
        let filter = $("#dropdown_btn").text().toLowerCase();
        let from_date = $("#from_date").val();
        let to_date = $("#to_date").val();

        $("input#dark_mode").prop("checked", $("html").is("[data-bs-theme]"));
        $('input[aria-label="export_filter"]').val(
            !filter.includes("search by") ? filter.replace(" ", "_") : ""
        );
        $('input[aria-label="export_search"]').val(
            !filter.includes("search by") && search ? search : ""
        );
        $('input[aria-label="export_from_date"]').val(
            from_date ? from_date + " 00:00:00" : ""
        );
        $('input[aria-label="export_to_date"]').val(
            to_date ? to_date + " 23:59:59" : ""
        );
    });
});
