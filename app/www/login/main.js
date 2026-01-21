import { rejectApiError } from "/js/errors.js"
import { navigateBack } from "/js/navigation.js"

const form = document.getElementById("loginForm")
const backButton = document.getElementById("back-button")

backButton.addEventListener("click", (e) => {
    e.preventDefault()
    navigateBack()
    return false
})

form.addEventListener("submit", async (e) => {
    e.preventDefault()

    const data = new FormData(form)
    const res = await fetch(form.action, {
        method: form.method,
        body: data,
    }).then(rejectApiError)

    if (res.ok) {
        navigateBack()
    }
})