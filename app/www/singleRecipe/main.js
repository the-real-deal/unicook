import { rejectApiError } from "/js/errors.js"
import { saveRecipe } from "/js/recipeCard.js"

const reviewTextArea = document.getElementById('review-text-area')
const saveBtn = document.getElementById('save_btn_1')
const deleteBtn = document.getElementById('remove_btn')
const params = new URLSearchParams(document.location.search).get("recipeId")
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