const setTheme = (mode) => {
    const html = document.documentElement;
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

const changeSelected = (mode) => {
    if (mode === "auto") {
        $("#bd-theme-text").text("Auto");
        $("#bd-theme-icon").attr("href", "#circle-half");
    } else if (mode === "dark") {
        $("#bd-theme-text").text("Dark");
        $("#bd-theme-icon").attr("href", "#moon-stars-fill");
    } else if (mode === "light") {
        $("#bd-theme-text").text("Light");
        $("#bd-theme-icon").attr("href", "#sun-fill");
    }
};

const applyTheme = (mode) => {
    changeSelected(mode);
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

obs = new MutationObserver((_, obs) => {
    const input = $("#bd-theme-text");
    if (input.length) {
        changeSelected(localStorage.getItem("theme") || "auto");
        obs.disconnect();
    }
});
obs.observe(document.documentElement, { childList: true, subtree: true });

$(() => {
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
