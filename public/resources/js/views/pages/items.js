$(() => {
    $("[data-bs-target='#descriptionModal']").on("click", (e) => {
        const button = $(e.currentTarget);
        const itemName = button.data("item-name");
        const description = button.data("description");

        $("#modalItemName").text(itemName);
        $("#modalDescriptionText").text(description);

        resetCopyButton();
    });

    let timeout;
    $("#copyDescriptionBtn").on("click", () => {
        navigator.clipboard
            .writeText($("#modalDescriptionText").text())
            .then(() => {
                clearTimeout(timeout);
                const copyBtn = $("#copyDescriptionBtn");

                copyBtn.html(
                    '<svg width="16" height="16" class="me-2 mb-1" aria-hidden="true"><use href="#clipboard2-check" fill="currentColor" /></svg>Copied!'
                );
                copyBtn
                    .removeClass("btn-outline-primary")
                    .addClass("btn-success");

                timeout = setTimeout(() => {
                    resetCopyButton();
                }, 2000);
            })
            .catch((err) => {
                console.error("Error copying text: ", err);
            });
    });

    function resetCopyButton() {
        const $copyBtn = $("#copyDescriptionBtn");
        $copyBtn.html(
            '<svg width="16" height="16" class="me-2 mb-1" aria-hidden="true"><use href="#clipboard2-plus" fill="currentColor" /></svg>Copy Description'
        );
        $copyBtn.removeClass("btn-success").addClass("btn-outline-primary");
    }
});
