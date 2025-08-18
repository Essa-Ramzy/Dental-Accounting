$(() => {
    $(window).on("pageshow", () => {
        sessionStorage.removeItem("customer");
        sessionStorage.removeItem("item");
        sessionStorage.removeItem("teeth");
        sessionStorage.removeItem("date");
        sessionStorage.removeItem("discount");
        sessionStorage.removeItem("discount_mode");
    });

    $(document).on(
        "click",
        "#dropdown_btn + .dropdown-menu .dropdown-item",
        (e) => {
            $("#dropdown_btn").text(e.currentTarget.textContent);
            $("#search").css(
                "padding-right",
                "calc(" +
                    ($("#dropdown_btn").outerWidth() / 6 + 1.5) +
                    " * 0.375rem)"
            );
            $("#search").trigger("change");
        }
    );

    $(document).on("input", "#search", () => {
        $("#search").trigger("change");
    });

    let range = $("#report_range");
    let change_date = (start, end) => {
        range.val(
            start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
        );
        $("#from_date").val(start.format("YYYY-MM-DD"));
        $("#to_date").val(end.format("YYYY-MM-DD"));
        ajax(
            $("meta[name='search-url']").attr("content"),
            $("#search").val().toLowerCase(),
            $("#dropdown_btn").text().toLowerCase(),
            start.format("YYYY-MM-DD"),
            end.format("YYYY-MM-DD")
        );
    };

    range.daterangepicker(
        {
            startDate: moment().subtract(29, "days"),
            endDate: moment(),
            parentEl: "body",
            cancelButtonClasses: "btn-secondary",
            linkedCalendars: false,
            autoUpdateInput: false,
            opens: "center",
            locale: {
                cancelLabel: "Clear",
            },
            alwaysShowCalendars: true,
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                    moment().subtract(1, "days"),
                    moment().subtract(1, "days"),
                ],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ],
                "Last Month": [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
            },
        },
        change_date
    );

    range.on("apply.daterangepicker", (_, picker) => {
        change_date(picker.startDate, picker.endDate);
    });

    range.on("cancel.daterangepicker", () => {
        range.val("Select Date Range");
        $("#from_date").val("");
        $("#to_date").val("");
        $("#search").trigger("change");
    });

    const filter_map = {
        single: "single",
        all: "all",
        id: "id",
        name: "name",
        "patient name": "name",
        treatment: "item",
        quantity: "amount",
        "unit price": "unit_price",
        discount: "discount",
        price: "price",
        "total price": "price",
        cost: "cost",
    };

    $(document).on("click", "a[href='#deleteModal']", (e) => {
        let search = $("#search").val().toLowerCase();
        let filter = $("#dropdown_btn").text().toLowerCase();
        if (e.currentTarget.id) {
            search = e.currentTarget.id;
            filter = "single";
        }
        let from_date = $("#from_date").val();
        let to_date = $("#to_date").val();

        $('input[aria-label="delete_filter"]').val(
            !filter.includes("search by") ? filter_map[filter] : ""
        );
        $('input[aria-label="delete_search"]').val(
            !filter.includes("search by") && search ? search : ""
        );
        $('input[aria-label="delete_from_date"]').val(
            from_date ? from_date + " 00:00:00" : ""
        );
        $('input[aria-label="delete_to_date"]').val(
            to_date ? to_date + " 23:59:59" : ""
        );
    });

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

    let ajax = (url, search, filter, from_date, to_date) => {
        $.ajax({
            url: url,
            type: "GET",
            data: {
                search:
                    filter && !filter.includes("search by") && search
                        ? search
                        : "",
                filter:
                    filter && !filter.includes("search by")
                        ? filter_map[filter]
                        : "",
                from_date: from_date ? from_date + " 00:00:00" : "",
                to_date: to_date ? to_date + " 23:59:59" : "",
            },
            success: (data) => {
                $("#table tbody").html(data.body);
                $("#table tfoot").html(data.footer);
                $("#pagination").html(data.links);
                initTeethPopovers();
            },
        });
    };

    $(document).on("click", "#pagination .page-link", (e) => {
        e.preventDefault();
        let url = e.currentTarget.getAttribute("href");
        if (url) {
            url = new URL(url);
            let params = new URLSearchParams(url.search);
            ajax(
                url.origin + url.pathname + "?page=" + params.get("page"),
                params.get("search"),
                params.get("filter"),
                params.get("from_date"),
                params.get("to_date")
            );
        }
    });

    $("#search").on("change", () => {
        let search = $("#search").val().toLowerCase();
        let filter = $("#dropdown_btn").text().toLowerCase();
        let from_date = $("#from_date").val();
        let to_date = $("#to_date").val();
        if (!filter.includes("search by")) {
            ajax(
                $("meta[name='search-url']").attr("content"),
                search,
                filter,
                from_date,
                to_date
            );
        }
    });
});
