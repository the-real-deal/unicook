import { rejectApiError } from "/js/errors.js"
import { saveRecipe } from "/js/recipeCard.js"
import { navigateBack } from "/js/navigation.js"

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
        const response = await fetch(form.action, {
            method: form.method,
            body: data
        }).then(rejectApiError)

        if (response.ok) {

            const templateContainer = document.getElementById("review-template")
            const clone = templateContainer.querySelector("article").cloneNode(true)

            const clone_datetime = clone.querySelector("time");
            clone_datetime.datetime = new Date().toISOString();
            clone_datetime.textContent = new Date().toLocaleString(undefined, {
                year: "numeric",
                month: "numeric",
                day: "numeric"
            });
            clone.querySelector("p").textContent = "LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL";
            const rating = 3
            clone.querySelector("span").textContent = rating;

            const full_star = clone.querySelector("div:has(> svg) svg:first-of-type").cloneNode(true)
            const empty_star = clone.querySelector("div:has(> svg) svg:last-of-type").cloneNode(true)
            const star_div = clone.querySelector("div:has(> svg)")
            star_div.innerHTML = ""

            const span = document.createElement("span");
            span.textContent = "(" + rating + ")";
            star_div.appendChild(span);

            star_div.append(document.createElement("span"))
            for (let index = 0; index < 5; index++) {
                if (index < rating) {
                    star_div.append(full_star.cloneNode(true))
                } else {
                    star_div.append(empty_star.cloneNode(true))
                }

            }

            document.getElementById("reviews-box").prepend(clone)
        }
    })



}

if (saveBtn) {
    saveBtn.addEventListener('click', (e) => {
        saveRecipe('save_btn_1', params)
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
    })
}

backButton.addEventListener("click", (e) => {
    e.preventDefault()
    console.log(document.referrer)
    console.log(window.location.origin)

    navigateBack()
    return false
})

async function addRecipeFromTemplate(fragment, recipeData) {
    const template = document.getElementById("{template}")
    const clone = template.cloneNode(true)
}

