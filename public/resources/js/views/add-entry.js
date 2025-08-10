$(() => {
    let updateReceipt = ({
        unitPrice = null,
        amount = null,
        discount = null,
    }) => {
        currency_mode = $(".mode-btn.active").data("mode") === "currency";
        if (unitPrice === null) {
            unitPrice = parseInt(
                $("#receipt-unit-price").text().replace("E£ ", "")
            );
        }
        if (amount === null) {
            amount = parseInt($("#receipt-amount").text());
        }
        if (discount === null) {
            discount = parseFloat(
                $("#receipt-discount")
                    .text()
                    .replace(/^(E£|%)\s*/, "") || 0
            ).toFixed(2);
        }
        if (currency_mode) {
            total = unitPrice * amount - discount;
        } else {
            total = (unitPrice * amount * (100 - discount)) / 100;
        }
        $("#receipt-unit-price").text(`E£ ${unitPrice}`);
        $("#receipt-amount").text(amount);
        $("#receipt-discount").text(
            `${currency_mode ? "E£" : "%"} ${discount}`
        );
        $("#receipt-total").text(`E£ ${total.toFixed(2)}`);
    };

    $(document).on("change", "#name", (e) => {
        const select = $(e.currentTarget);
        if (!select.val()) {
            sessionStorage.removeItem("customer");
            select.selectpicker("val", "undefined");
            window.location = document
                .querySelector('meta[name="customer-create-url"]')
                .getAttribute("content");
        } else {
            sessionStorage.setItem("customer", select.val());
        }
    });

    $(document).on("change", "#item", (e) => {
        const select = $(e.currentTarget);
        updateReceipt({ unitPrice: select.find(":selected").data("price") });
        if (!select.val()) {
            sessionStorage.removeItem("item");
            select.selectpicker("val", "undefined");
            window.location = document
                .querySelector('meta[name="item-create-url"]')
                .getAttribute("content");
        } else {
            sessionStorage.setItem("item", select.val());
        }
    });

    $(document).on("change", "#date", (e) => {
        sessionStorage.setItem("date", $(e.currentTarget).val());
    });

    $(document).on("change", "#discount", (e) => {
        discount = parseFloat($(e.currentTarget).val()).toFixed(2);
        sessionStorage.setItem("discount", discount);
        updateReceipt({ discount });
    });

    $(document).on("click", ".mode-btn", (e) => {
        let input = $("#discount");
        let oldValue = parseFloat(input.val()) || 0;
        let oldMode = $(".mode-btn.active").data("mode");
        let newMode = $(e.currentTarget).data("mode");

        $(e.currentTarget).siblings(".mode-btn").removeClass("active");
        $(e.currentTarget).addClass("active");
        sessionStorage.setItem("discount_mode", newMode);

        if (oldMode !== newMode) {
            let amount = $("#teeth").val()?.length || 0;
            let price = $("#item").find(":selected")?.data("price") || 0;
            let total = amount * price;

            if (total > 0) {
                if (newMode === "currency") {
                    // Percent → Currency
                    newValue = (oldValue / 100) * total;
                } else {
                    // Currency → Percent
                    newValue = (oldValue / total) * 100;
                }
            } else {
                newValue = 0;
            }
        }
        newValue = newValue.toFixed(2);

        input.val(isFinite(newValue) && newValue != 0 ? newValue : "");
        updateReceipt({ discount: newValue });
    });

    $("#name.selectpicker").selectpicker({
        noneResultsText: `<a href="${document
            .querySelector('meta[name="customer-create-url"]')
            .getAttribute(
                "content"
            )}" class="d-block text-decoration-none w-auto dropdown-item active" style="margin: -0.1875rem -0.5rem; padding: 0.25rem 1rem;">Create New Customer</a>`,
    });

    $("#item.selectpicker").selectpicker({
        noneResultsText: `<a href="${document
            .querySelector('meta[name="item-create-url"]')
            .getAttribute(
                "content"
            )}" class="d-block text-decoration-none w-auto dropdown-item active" style="margin: -0.1875rem -0.5rem; padding: 0.25rem 1rem;">Create New Item</a>`,
    });

    $("#teeth.selectpicker").selectpicker({
        noneResultsText:
            '<span class="bg-body d-block" style="margin: -0.1875rem; padding: 0.25rem 0.6875rem;">No results matched</span>',
    });

    // Tab switching logic
    const tabs = $("#teethTab .nav-link");
    const panes = $("#teethTabContent .tab-pane");

    tabs.on("click", (e) => {
        e.preventDefault();

        tabs.removeClass("active");
        panes.removeClass("show active");

        $(e.currentTarget).addClass("active");

        const target = $(e.currentTarget).data("target");
        const pane = $(target);

        if (pane.length) {
            pane.addClass("show active");
        }
    });

    // Toggle selection color on SVG teeth and sync with dropdown
    $("#Spots [data-key]").on("click", function (e) {
        const el = $(e.currentTarget);
        el[0].toggleAttribute("selected");
        // compute select value from data-key
        const key = el.data("key");
        const num = ((key - 1) % 8) + 1;
        let prefix =
            key <= 8 ? "UR-" : key <= 16 ? "UL-" : key <= 24 ? "LR-" : "LL-";
        const val = prefix + num;
        // sync with select
        const select = $("#teeth");
        let selected = select.val() || [];
        select.selectpicker("destroy");
        if (el[0].hasAttribute("selected")) {
            if (!selected.includes(val)) selected.push(val);
        } else {
            selected = selected.filter((v) => v !== val);
        }
        select.val(selected);
        select.selectpicker();
        sessionStorage.setItem("teeth", selected);
        updateReceipt({ amount: selected.length });
    });
    // When list selection changes, sync SVG
    $("#teeth").on("change", (e) => {
        const selected = $(e.currentTarget).val() || [];
        $("#Spots [data-key]").each((_, ee) => {
            const el = $(ee);
            const key = parseInt(el.data("key"), 10);
            const num = ((key - 1) % 8) + 1;
            let prefix =
                key <= 8
                    ? "UR-"
                    : key <= 16
                    ? "UL-"
                    : key <= 24
                    ? "LR-"
                    : "LL-";
            const val = prefix + num;
            if (selected.includes(val) != el[0].hasAttribute("selected")) {
                el[0].toggleAttribute("selected");
            }
        });
        sessionStorage.setItem("teeth", selected);
        updateReceipt({ amount: selected.length });
    });

    if (performance.getEntriesByType("navigation")[0].type === "reload") {
        sessionStorage.removeItem("customer");
        sessionStorage.removeItem("item");
        sessionStorage.removeItem("teeth");
        sessionStorage.removeItem("date");
        sessionStorage.removeItem("discount");
        sessionStorage.removeItem("discount_mode");
    }

    if ($("#name").val()) {
        sessionStorage.setItem("customer", $("#name").val());
    } else if (sessionStorage.getItem("customer")) {
        $("#name").selectpicker("val", sessionStorage.getItem("customer"));
    }

    if ($("#item").val()) {
        sessionStorage.setItem("item", $("#item").val());
    } else if (sessionStorage.getItem("item")) {
        $("#item").selectpicker("val", sessionStorage.getItem("item"));
    }

    if (sessionStorage.getItem("teeth")) {
        $("#teeth")
            .selectpicker("val", sessionStorage.getItem("teeth").split(","))
            .trigger("change");
        updateReceipt({ amount: sessionStorage.getItem("teeth").split(",").length });
    }

    if (sessionStorage.getItem("date")) {
        $("#date").val(sessionStorage.getItem("date"));
    }

    if (sessionStorage.getItem("discount")) {
        $("#discount").val(sessionStorage.getItem("discount"));
        updateReceipt({ discount: sessionStorage.getItem("discount") });
    }

    if (sessionStorage.getItem("discount_mode")) {
        $(".mode-btn").removeClass("active");
        $(".mode-btn[data-mode='" + sessionStorage.getItem("discount_mode") + "']").addClass("active");
    }
});
