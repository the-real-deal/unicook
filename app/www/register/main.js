import { rejectApiError, showError } from "/js/errors.js"
import { navigateBack } from "/js/navigation.js"

const form = document.getElementById("registerForm")
const backButton = document.getElementById("back-button")

backButton.addEventListener("click", (e) => {
    e.preventDefault()
    navigateBack()
    return false
})

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

    const res = await fetch(form.action, {
        method: form.method,
        body: data,
    }).then(rejectApiError)

    if (res.ok) {
        window.location = "/"
    }
})

