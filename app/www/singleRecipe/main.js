import { rejectApiError } from "/js/errors.js"
import { saveRecipe } from "/js/recipeCard.js"
import { navigateBack } from "/js/navigation.js"
import { hydrateReviewElement } from "/js/review.js"

const reviewTextArea = document.getElementById('review-text-area')
const saveBtn = document.getElementById('save_btn_1')
const deleteBtn = document.getElementById('remove_btn')
const params = new URLSearchParams(document.location.search).get("recipeId")
const backButton = document.getElementById("back-button")
const form = document.getElementById('review-form')

if (form) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault()

        const data = new FormData(form)
        data.set('recipeId', params)
        const res = await fetch(form.action, {
            method: form.method,
            body: data
        }).then(rejectApiError)
            .then(r => r.json())

        const review = await fetch(`/api/reviews/data.php?reviewId=${res.id}`)
            .then(rejectApiError)
            .then(r => r.json())

        const templateContainer = document.getElementById("review-template")
        const clone = templateContainer.querySelector("article").cloneNode(true)

        clone.dataset.reviewid = review.id
        const clone_datetime = clone.querySelector("time")

        const date = new Date(review.createdAt.date)
        clone_datetime.datetime = date.toISOString()
        clone_datetime.textContent = date.toLocaleString(undefined, {
            year: "numeric",
            month: "numeric",
            day: "numeric"
        })
        clone.querySelector("p").textContent = review.body
        const rating = review.rating
        clone.querySelector("span").textContent = rating

        const full_star = clone.querySelector("div:has(> svg) svg:first-of-type").cloneNode(true)
        const empty_star = clone.querySelector("div:has(> svg) svg:last-of-type").cloneNode(true)
        const star_div = clone.querySelector("div:has(> svg)")
        star_div.innerHTML = ""

        const span = document.createElement("span")
        span.textContent = "(" + rating + ")"
        star_div.appendChild(span)

        star_div.append(document.createElement("span"))
        for (let index = 0; index < 5; index++) {
            if (index < rating) {
                star_div.append(full_star.cloneNode(true))
            } else {
                star_div.append(empty_star.cloneNode(true))
            }

        }

        document.getElementById("reviews-box").prepend(clone)
        hydrateReviewElement(clone)
    })
}

if (saveBtn) {
    saveBtn.addEventListener('click', async (e) => {
        await saveRecipe('save_btn_1', params)
    })
}

if (reviewTextArea) {
    reviewTextArea.oninput = function () {
        this.style.height = ""
        this.style.height = (this.scrollHeight + 3) + 'px'
    }
}

if (deleteBtn) {
    deleteBtn.addEventListener('click', async (e) => {
        const formData = new FormData()
        formData.set('recipeId', params)

        const response = await fetch("/api/recipes/delete.php", {
            method: "POST",
            body: formData
        }).then(rejectApiError)

        if (response.ok) {
            navigateBack()
        }
    })
}

backButton.addEventListener("click", (e) => {
    e.preventDefault()
    navigateBack()
    return false
})

