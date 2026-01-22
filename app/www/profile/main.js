import { rejectApiError } from "/js/errors.js"
import { navigateBack } from "/js/navigation.js"

const form = document.getElementById("imageForm")
const avatarImage = document.getElementById("avatarImage")
const uploadButton = document.getElementById("upload-button")
const imageInput = document.getElementById('image')
const logoutButton = document.getElementById('logout-button')
const deleteImageButton = document.getElementById("delete-image-button")
const changeAdminButton = document.getElementById("change-admin")
const deleteUserButton = document.getElementById("delete-user")
const navUserImage = document.querySelector("nav div.navbar-nav li:last-child a img")

async function getUser() {
    const params = new URLSearchParams(document.location.search)
    const userId = params.get("userId")
    const user = await fetch(`/api/users/data.php?userId=${userId}`)
        .then(rejectApiError)
        .then(res => res.json())
    return user
}

function setImageSrc(user, noCache = false) {
    const src = user.avatarId === null ?
        `https://ui-avatars.com/api/?name=${user.username}&size=256` :
        `/api/users/image/content.php?userId=${user.id}${noCache ? `&t=${Date.now()}` : ""}`
    avatarImage.src = src
    if (navUserImage && navUserImage.dataset.userid === user.id) {
        navUserImage.src = src
    }
}

form?.addEventListener("submit", async (e) => {
    e.preventDefault()

    const data = new FormData(form)
    const res = await fetch(form.action, {
        method: form.method,
        body: data,
    }).then(rejectApiError)

    if (res.ok) {
        const user = await getUser()
        setImageSrc(user, true)
        form.reset()
        uploadButton.style.visibility = 'hidden'
    }
})

const user = await getUser()
setImageSrc(user)


imageInput?.addEventListener('change', function () {
    if (this.files && this.files.length > 0) {
        uploadButton.style.visibility = 'visible'
    } else {
        uploadButton.style.visibility = 'hidden'
    }
})

logoutButton?.addEventListener('click', async (e) => {
    const res = await fetch('/api/auth/logout.php', { method: 'POST' })
        .then(rejectApiError)

    if (res.ok) {
        window.location = '/login/'
    }
})

deleteImageButton?.addEventListener("click", async (e) => {
    let user = await getUser()

    const data = new FormData()
    data.set("userId", user.id)
    const res = await fetch('/api/users/image/delete.php', {
        method: 'POST',
        body: data,
    }).then(rejectApiError)


    if (res.ok) {
        user = await getUser()
        setImageSrc(user, true)
    }
})

function setAdminButton() {
    if (!changeAdminButton) {
        return
    }

    const admin = changeAdminButton.hasAttribute("data-admin")
    const revokeText = "Revoke admin"
    const grantText = "Grant admin"
    const index = changeAdminButton.innerHTML.lastIndexOf(admin ? grantText : revokeText)
    if (index !== -1) {
        changeAdminButton.innerHTML = changeAdminButton.innerHTML.substring(0, index)
    }
    changeAdminButton.innerHTML += admin ? revokeText : grantText
}

if (changeAdminButton) {
    changeAdminButton.addEventListener("click", async (e) => {
        changeAdminButton.disabled = true
        const user = await getUser()
        const admin = changeAdminButton.hasAttribute("data-admin")
        const newAdmin = !admin

        if (newAdmin) {
            changeAdminButton.setAttribute("data-admin", true)
        } else {
            changeAdminButton.removeAttribute("data-admin")
        }

        const data = new FormData()
        data.set("userId", user.id)
        data.set("admin", newAdmin)

        const res = await fetch("/api/users/update.php", {
            method: "POST",
            body: data,
        }).then(rejectApiError)
        if (res.ok) {
            setAdminButton()
            changeAdminButton.disabled = false
        }
    })

    setAdminButton()
}

deleteUserButton?.addEventListener("click", async (e) => {
    const user = await getUser()

    const data = new FormData()
    data.set("userId", user.id)

    const res = await fetch("/api/users/delete.php", {
        method: "POST",
        body: data,
    }).then(rejectApiError)
    if (res.ok) {
        navigateBack()
    }
})
