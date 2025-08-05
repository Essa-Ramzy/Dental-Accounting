$(document).on("change", "#name", (e) => {
    if (!e.target.value) {
        $(e.target).selectpicker("val", "undefined");
        window.location = document
            .querySelector('meta[name="customer-create-url"]')
            .getAttribute("content");
    }
});

$(document).on("change", "#item", (e) => {
    if (!e.target.value) {
        $(e.target).selectpicker("val", "undefined");
        window.location = document
            .querySelector('meta[name="item-create-url"]')
            .getAttribute("content");
    }
});

$(() => {
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
});
