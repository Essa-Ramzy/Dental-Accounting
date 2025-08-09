$(() => {
    $(document).on("change", "#name", (e) => {
        if (!e.currentTarget.value) {
            sessionStorage.removeItem("customer");
            $(e.currentTarget).selectpicker("val", "undefined");
            window.location = document
                .querySelector('meta[name="customer-create-url"]')
                .getAttribute("content");
        } else {
            sessionStorage.setItem("customer", $(e.currentTarget).val());
        }
    });

    $(document).on("change", "#item", (e) => {
        if (!e.currentTarget.value) {
            sessionStorage.removeItem("item");
            $(e.currentTarget).selectpicker("val", "undefined");
            window.location = document
                .querySelector('meta[name="item-create-url"]')
                .getAttribute("content");
        } else {
            sessionStorage.setItem("item", $(e.currentTarget).val());
        }
    });

    $(document).on("change", "#date", (e) => {
        sessionStorage.setItem("date", $(e.currentTarget).val());
    });

    $(document).on("change", "#discount", (e) => {
        sessionStorage.setItem("discount", $(e.currentTarget).val());
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
    });

    if (performance.getEntriesByType("navigation")[0].type === "reload") {
        sessionStorage.removeItem("customer");
        sessionStorage.removeItem("item");
        sessionStorage.removeItem("teeth");
        sessionStorage.removeItem("date");
        sessionStorage.removeItem("discount");
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
        $("#teeth").selectpicker(
            "val",
            sessionStorage.getItem("teeth").split(",")
        ).trigger("change");
    }

    if (sessionStorage.getItem("date")) {
        $("#date").val(sessionStorage.getItem("date"));
    }

    if (sessionStorage.getItem("discount")) {
        $("#discount").val(sessionStorage.getItem("discount"));
    }
});
