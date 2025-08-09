$(() => {
    sessionStorage.removeItem("customer");
    sessionStorage.removeItem("item");
    sessionStorage.removeItem("teeth");
    sessionStorage.removeItem("date");
    sessionStorage.removeItem("discount");

    $(document).on("click", ".dropdown-item", (e) => {
        $("#dropdown_btn").text(e.currentTarget.textContent);
        $("#search").trigger("change");
    });

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
        $("#search").trigger("change");
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
        range.val("Date Range");
        $("#from_date").val("");
        $("#to_date").val("");
        $("#search").trigger("change");
    });
});
