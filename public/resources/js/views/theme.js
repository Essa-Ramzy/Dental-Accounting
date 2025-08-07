$(() => {
    const setTheme = (mode) => {
        const html = $("html");

        if (mode === "system") {
            const prefersDark = window.matchMedia(
                "(prefers-color-scheme: dark)"
            ).matches;
            html.attr("data-bs-theme", prefersDark ? "dark" : "light");
        } else if (mode === "light" || mode === "dark") {
            html.attr("data-bs-theme", mode);
        } else {
            console.warn("Invalid theme mode:", mode);
        }
    };

    setTheme("system");

    window
        .matchMedia("(prefers-color-scheme: dark)")
        .addEventListener("change", () => {
            if (
                $("html").attr("data-bs-theme") !== "dark" &&
                $("html").attr("data-bs-theme") !== "light"
            ) {
                setTheme("system");
            }
        });
});
