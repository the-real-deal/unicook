import { rejectApiError } from '/js/errors.js'

window.onload = function () {
    const buttons = document.querySelectorAll('.recipe-card')
    buttons.forEach(b => {
        const button = b.querySelector('article>button')
        button?.addEventListener('click', (e) => {
            saveRecipe(button.id, b.querySelector('.recipe-card-title').dataset.recipeId)
        })
    })
}

export async function saveRecipe(btnId, recipeId) {

    const btn = document.getElementById(btnId)
    const data = new FormData()

    data.set("recipeId", recipeId)

    let api = "/api/users/recipes/saves/"

    if (btn.querySelector("svg").attributes.fill.value == "transparent") {
        btn.title = "remove from saved"
        btn.querySelector("svg").attributes.fill.value = "currentColor"
        api += "save.php"
    }
    else {
        btn.title = "save"
        btn.querySelector("svg").attributes.fill.value = "transparent"
        api += "unsave.php"
    }

    await fetch(api, {
        method: "POST",
        body: data,
    }).then(rejectApiError)
}

function deleteRecipe(recipeId, elementId) {
    const recipeCard = document.getElementById(elementId)
    if (recipeCard) {
        recipeCard.remove()
    }
}

Array.from(document.getElementsByClassName('recipe-card-title')).forEach(element => {
    element.addEventListener('click', () => {
        changePage(element.dataset.recipeId)
    })
})

export function changePage(recipeID) {
    window.location = "/singleRecipe?recipeId=" + recipeID
}