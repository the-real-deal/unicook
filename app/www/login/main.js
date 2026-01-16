import { rejectApiError } from "/js/errors.js"

const form = document.getElementById("loginForm")
form.addEventListener("submit", async (e) => {
    e.preventDefault()

    const result = await fetch(form.action, {
        method: form.method,
        body: new FormData(form),
    })
        .then(rejectApiError)
        .then((res) => res.json())
    if (result.ok) {
        window.location = "/"
    }
})

