const storageKey = "theme";

function applyTheme(theme) {
    const root = document.documentElement;
    if (theme === "dark") root.classList.add("dark");
    else root.classList.remove("dark");
}

function getPreferredTheme() {
    const stored = localStorage.getItem(storageKey);
    if (stored === "dark" || stored === "light") return stored;
    return window.matchMedia("(prefers-color-scheme: dark)").matches
        ? "dark"
        : "light";
}

applyTheme(getPreferredTheme());

window.addEventListener("DOMContentLoaded", () => {
    const btn = document.querySelector("[data-theme-toggle]");
    if (!btn) return;

    btn.addEventListener("click", () => {
        const current = document.documentElement.classList.contains("dark")
            ? "dark"
            : "light";
        const next = current === "dark" ? "light" : "dark";
        localStorage.setItem(storageKey, next);
        applyTheme(next);
    });
});
