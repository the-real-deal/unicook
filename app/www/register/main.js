import { rejectApiError, showError } from "/js/errors.js"

const form = document.getElementById("registerForm")

form.addEventListener("submit", async (e) => {
    e.preventDefault()

    const data = new FormData(form)
    const password = data.get("password")
    const confirmPassword = data.get("confirmPassword")
    if (password !== confirmPassword) {
        showError("Passwords do not match")
        return
    }
    data.delete("confirmPassword")

    const result = await fetch(form.action, {
        method: form.method,
        body: data,
    })
        .then(rejectApiError)
        .then((res) => res.json())
    if (result.ok) {
        window.location = "/"
    }
})

