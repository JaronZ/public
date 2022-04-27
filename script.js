document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const urlInput = form.elements.namedItem("url");
        const url = urlInput.value;

        const xhr = new XMLHttpRequest();
        xhr.addEventListener("load", () => {
            if (xhr.status === 200) {
                const {url} = JSON.parse(xhr.responseText);
                urlInput.value = `urlshortener.test/${url}`;
            }
        });
        xhr.open("POST", "/");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify({url}));
    });
});