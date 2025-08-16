const setTheme = (mode) => {
    const html = document.querySelector("html");
    if (mode === "auto") {
        if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
            html.setAttribute("data-bs-theme", "dark");
        } else {
            html.removeAttribute("data-bs-theme");
        }
    } else if (mode === "dark") {
        html.setAttribute("data-bs-theme", "dark");
    } else if (mode === "light") {
        html.removeAttribute("data-bs-theme");
    } else {
        console.warn("Invalid theme mode:", mode);
    }
};

const applyTheme = (mode) => {
    const text = mode === "dark" ? "Dark" : mode === "light" ? "Light" : "Auto";
    const icon =
        mode === "dark"
            ? "moon-stars-fill"
            : mode === "light"
            ? "sun-fill"
            : "circle-half";
    $("#bd-theme-text").text(text);
    $("#bd-theme-icon").attr("href", `#${icon}`);
    localStorage.setItem("theme", mode);
    setTheme(mode);
};

window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", () => {
        const stored = localStorage.getItem("theme") || "auto";
        if (stored === "auto") {
            setTheme("auto");
        }
    });

applyTheme(localStorage.getItem("theme") || "auto");

$(() => {
    applyTheme(localStorage.getItem("theme") || "auto");
    theme_buttons = $("[data-bs-theme-value]");
    change_selected = (mode) => {
        theme_buttons.attr("aria-pressed", "false").removeClass("active");
        theme_buttons
            .filter(`[data-bs-theme-value="${mode}"]`)
            .attr("aria-pressed", "true")
            .addClass("active");
        $("[data-bs-theme-value] svg:has([href='#check-lg'])").addClass(
            "d-none"
        );
        $(
            "[data-bs-theme-value].active svg:has([href='#check-lg'])"
        ).removeClass("d-none");
    };
    change_selected(localStorage.getItem("theme") || "auto");
    theme_buttons.on("click", (e) => {
        const theme = $(e.currentTarget).attr("data-bs-theme-value");
        change_selected(theme);
        applyTheme(theme);
    });
});
