async function saveRecipe(btnId, recipeId) {

    const btn = document.getElementById(btnId)
    if (btn.querySelector("svg").attributes.fill.value == "transparent") {

        btn.title = "remove from saved"
        btn.querySelector("svg").attributes.fill.value = "currentColor"
    }
    else {

        btn.title = "save"
        btn.querySelector("svg").attributes.fill.value = "transparent"
    }
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

function changePage(recipeID) {
    window.location = "/singleRecipe?recipeId=" + recipeID
}