import { rejectApiError } from "/js/errors.js"

const form = document.getElementById("imageForm")
const avatarImage = document.getElementById("avatarImage")
const uploadButton = document.getElementById("upload-button")
const imageInput = document.getElementById('image')

async function getUser() {
    const params = new URLSearchParams(document.location.search)
    const userId = params.get("userId")
    const user = await fetch(`/api/users/data.php?userId=${userId}`)
        .then(rejectApiError)
        .then(res => res.json())
    return user
}

function getFallbackSrc(user) {
    return `https://ui-avatars.com/api/?name=${user.username}&size=256`
}

function getImageSrc(user, noCache = false) {
    return `/api/users/image/content.php?userId=${user.id}${noCache ? `&t=${Date.now()}` : ""}`
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
        avatarImage.src = getImageSrc(user, true)
        form.reset()
        uploadButton.style.visibility = 'hidden'
    }
})

const user = await getUser()

avatarImage.addEventListener("error", _ => {
    avatarImage.src = getFallbackSrc(user)
})

avatarImage.src = getImageSrc(user)


imageInput.addEventListener('change', function () {
    if (this.files && this.files.length > 0) {
        uploadButton.style.visibility = 'visible'
    } else {
        uploadButton.style.visibility = 'hidden'
    }
})

document.getElementById('logout-button').addEventListener('click', async (e) => {
    const res = await fetch('/api/auth/logout.php', { method: 'POST' })

    if (res.ok) {
        window.location = '/login/'
    }
})
