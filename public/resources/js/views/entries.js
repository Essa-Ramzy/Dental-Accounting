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
            const myAllowList = bootstrap.Popover.Default.allowList;

            // // 2. Add all your necessary SVG tags and attributes
            // myAllowList.svg = [
            //     "version",
            //     "xmlns",
            //     "xmlns:xlink",
            //     "x",
            //     "y",
            //     "viewBox",
            //     "enable-background",
            //     "xml:space",
            // ];
            // myAllowList.g = ["id"];
            // myAllowList.text = ["id", "transform", "font-family", "font-size"];
            // myAllowList.polygon = ["id", "fill", "data-key", "points", "selected"];
            // myAllowList.path = ["id", "fill", "data-key", "d", "selected"];

            new bootstrap.Popover(el[0], {
                html: true,
                container: "body",
                content: content.html(),
                // allowList: myAllowList
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
                $(".table-responsive tbody").html(data["body"]);
                $(".table-responsive tfoot").html(data["footer"]);
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
