import { rejectApiError, showError } from "/js/errors.js"

const form = document.getElementById("imageForm")
const avatarImage = document.getElementById("avatarImage")

async function getUser() {
    const params = new URLSearchParams(document.location.search)
    const userId = params.get("userId")
    const user = await fetch(`/api/users/user.php?userId=${userId}`)
        .then(rejectApiError)
        .then(res => res.json())
    return user
}

function getFallbackSrc(user) {
    return `https://ui-avatars.com/api/?name=${user.username}&size=256`
}

function getImageSrc(user) {
    return `/api/users/image/content.php?userId=${user.id}`
}

form.addEventListener("submit", async (e) => {
    e.preventDefault()

    const data = new FormData(form)
    const res = await fetch(form.action, {
        method: form.method,
        body: data,
    }).then(rejectApiError)

    if (res.ok) {
        const user = await getUser()
        avatarImage.src = getImageSrc(user)
    }
})

const user = await getUser()

avatarImage.addEventListener("error", _ => {
    avatarImage.src = getFallbackSrc(user)
})

avatarImage.src = getImageSrc(user)

