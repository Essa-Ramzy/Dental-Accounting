$(() => {
    let updateReceipt = ({
        unitPrice = null,
        amount = null,
        discount = null,
    }) => {
        currency_mode =
            $(".discount-mode-btn.active").data("mode") === "currency";
        if (unitPrice === null) {
            unitPrice = parseFloat(
                $("#receipt-unit-price")
                    .text()
                    .replace("£ ", "")
                    .replace(/,/g, "")
            );
        }
        if (amount === null) {
            amount = parseInt($("#receipt-amount").text());
        }
        if (discount === null) {
            discount = parseFloat(
                $("#receipt-discount")
                    .text()
                    .replace(/^(£|%)\s*/, "")
                    .replace(/,/g, "") || 0
            );
        }
        if (currency_mode) {
            total = unitPrice * amount - discount;
        } else {
            total = (unitPrice * amount * (100 - discount)) / 100;
        }
        $("#receipt-unit-price").text(
            `£ ${Number(unitPrice).toLocaleString("en-US", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            })}`
        );
        $("#receipt-amount").text(amount);
        $("#receipt-discount").text(
            `${currency_mode ? "£" : "%"} ${Number(discount).toLocaleString(
                "en-US",
                {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2,
                }
            )}`
        );
        $("#receipt-total").text(
            `£ ${total.toLocaleString("en-US", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            })}`
        );
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
        updateReceipt({
            unitPrice: select.find(":selected").data("price"),
        });
        if (!select.val()) {
            sessionStorage.removeItem("item");
            select.selectpicker("val", "undefined");
            window.location = document
                .querySelector('meta[name="item-create-url"]')
                .getAttribute("content");
        } else {
            sessionStorage.setItem("item", select.val());
            $(".price-mode-btn.active").click();
        }
    });

    $(document).on("click", ".price-mode-btn", (e) => {
        item = $("#item").find(":selected");
        $(".price-mode-btn").removeClass("active");
        $(e.currentTarget).addClass("active");
        if (!item.data("new-price")) {
            $("#price").val(item.data("price"));
            $("#cost").val("");
        } else if ($(e.currentTarget).data("mode") === "old") {
            item.data("price", item.data("old-price"));
            $("#price").val(item.data("old-price"));
            $("#cost").val(item.data("old-cost"));
        } else {
            item.data("price", item.data("new-price"));
            $("#price").val(item.data("new-price"));
            $("#cost").val(item.data("new-cost"));
        }
        updateReceipt({ unitPrice: item.data("price") });
    });
    $(".price-mode-btn.active").click();

    $(document).on("change", "#date", (e) => {
        sessionStorage.setItem("date", $(e.currentTarget).val());
    });

    $(document).on("change", "#discount", (e) => {
        discount = parseFloat($(e.currentTarget).val()).toFixed(2);
        sessionStorage.setItem(
            "discount",
            Number(discount).toLocaleString({
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            })
        );
        updateReceipt({ discount });
    });

    $(document).on("click", ".discount-mode-btn", (e) => {
        let discount = $("#discount");
        let Value = parseFloat(discount.val()) || 0;
        let oldMode = $(".discount-mode-btn.active").data("mode");
        let newMode = $(e.currentTarget).data("mode");

        $(e.currentTarget).siblings(".discount-mode-btn").removeClass("active");
        $(e.currentTarget).addClass("active");
        sessionStorage.setItem("discount_mode", newMode);

        if (oldMode !== newMode) {
            let amount = $("#teeth").val().length || 0;
            let price = $("#item").find(":selected").data("price") || 0;
            let total = amount * price;

            if (total > 0) {
                if (newMode === "currency") {
                    // Percent → Currency
                    Value = (Value / 100) * total;
                } else {
                    // Currency → Percent
                    Value = (Value / total) * 100;
                }
            } else {
                Value = 0;
            }
        }

        discount
            .val(
                isFinite(Value) && Value != 0
                    ? Number(Value).toLocaleString({
                          minimumFractionDigits: 0,
                          maximumFractionDigits: 2,
                      })
                    : ""
            )
            .trigger("focus");
        updateReceipt({ discount: Value.toFixed(2) });
    });

    $("#name").selectpicker({
        noneResultsText: `<a href="${document
            .querySelector('meta[name="customer-create-url"]')
            .getAttribute(
                "content"
            )}" class="d-block text-decoration-none w-auto dropdown-item active" style="margin: -0.1875rem -0.5rem; padding: 0.25rem 1rem;">Create New Customer</a>`,
    });

    $("#item").selectpicker({
        noneResultsText: `<a href="${document
            .querySelector('meta[name="item-create-url"]')
            .getAttribute(
                "content"
            )}" class="d-block text-decoration-none w-auto dropdown-item active" style="margin: -0.1875rem -0.5rem; padding: 0.25rem 1rem;">Create New Item</a>`,
    });

    (($) => {
        // 1. Store a reference to the original function
        var originalCreateDropdown =
            $.fn.selectpicker.Constructor.prototype.createDropdown;

        // 2. The class you want to use instead of the default
        var newButtonClass = "btn-outline-secondary";

        // 3. The class you want to replace
        var classToReplace = "btn-light";

        // 4. Replace the original function with your own
        $.fn.selectpicker.Constructor.prototype.createDropdown = function () {
            // 5. Call the original function to get the dropdown HTML
            var $dropdown = originalCreateDropdown.apply(this, arguments);

            // 6. Find the action buttons and change their classes
            $dropdown
                .find(".bs-select-all, .bs-deselect-all")
                .removeClass(classToReplace)
                .addClass(newButtonClass);

            // 7. Return the modified dropdown
            return $dropdown;
        };
    })(jQuery);

    $("#teeth").selectpicker("destroy").selectpicker({
        noneResultsText:
            '<span class="bg-body d-block" style="margin: -0.1875rem; padding: 0.25rem 0.6875rem;">No results matched</span>',
        actionsBox: true,
        selectAllText:
            "<svg width='14' height='14' class='me-2 mb-1' aria-hidden='true'><use href='#check-all' fill='currentColor' /></svg>Select All",
        deselectAllText:
            "<svg width='14' height='14' class='me-2 mb-1' aria-hidden='true'><use href='#x-circle' fill='currentColor' /></svg>Clear All",
    });

    $("#select-all-teeth").on("click", () => {
        $("#Spots [data-key]").each((_, el) => {
            el.setAttribute("selected", "selected");
        });
        $("#teeth").selectpicker(
            "val",
            ["UR", "UL", "LR", "LL"]
                .map((prefix) => {
                    return [1, 2, 3, 4, 5, 6, 7, 8].map(
                        (num) => `${prefix}-${num}`
                    );
                })
                .flat()
        );
        sessionStorage.setItem("teeth", $("#teeth").val().join(","));
        updateReceipt({ amount: $("#teeth").val().length });
    });

    $("#clear-all-teeth").on("click", () => {
        $("#Spots [data-key]").each((_, el) => {
            el.removeAttribute("selected");
        });
        $("#teeth").selectpicker("val", "");
        sessionStorage.removeItem("teeth");
        updateReceipt({ amount: 0 });
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
        select.selectpicker({
            noneResultsText:
                '<span class="bg-body d-block" style="margin: -0.1875rem; padding: 0.25rem 0.6875rem;">No results matched</span>',
            actionsBox: true,
            selectAllText:
                "<svg width='14' height='14' class='me-2 mb-1' aria-hidden='true'><use href='#check-all' fill='currentColor' /></svg>Select All",
            deselectAllText:
                "<svg width='14' height='14' class='me-2 mb-1' aria-hidden='true'><use href='#x-circle' fill='currentColor' /></svg>Clear All",
        });
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

    if (
        performance.getEntriesByType("navigation")[0].type === "reload" ||
        $(".fade-out").length
    ) {
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
        updateReceipt({
            amount: sessionStorage.getItem("teeth").split(",").length,
        });
    }

    if (sessionStorage.getItem("date")) {
        $("#date").val(sessionStorage.getItem("date"));
    }

    if (sessionStorage.getItem("discount")) {
        $("#discount").val(sessionStorage.getItem("discount"));
        updateReceipt({ discount: sessionStorage.getItem("discount") });
    }

    if (sessionStorage.getItem("discount_mode")) {
        $(".discount-mode-btn").removeClass("active");
        $(
            ".discount-mode-btn[data-mode='" +
                sessionStorage.getItem("discount_mode") +
                "']"
        ).addClass("active");
    }

    $(document).on("submit", "form", (e) => {
        if ($(".discount-mode-btn.active").data("mode") === "percent") {
            $(".discount-mode-btn[data-mode='currency']").trigger("click");
            sessionStorage.setItem("discount_mode", "percent");
        }
    });

    updateReceipt({
        unitPrice: $("#item").find(":selected").data("price") || 0,
        amount: $("#teeth").val().length || 0,
        discount: parseFloat($("#discount").val() || 0),
    });
});
