import { rejectApiError } from "/js/errors.js"

const form = document.getElementById("loginForm")
form.addEventListener("submit", async (e) => {
    e.preventDefault()

    const data = new FormData(form)
    const res = await fetch(form.action, {
        method: form.method,
        body: data,
    }).then(rejectApiError)

    if (res.ok) {
        window.location = "/"
    }
})

