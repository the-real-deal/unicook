function saveRecipe(btnId, recipeId) {

    const btn = document.getElementById(btnId);
    console.log(btn.querySelector("svg").attributes.fill.value == "transparent");
    if (btn.querySelector("svg").attributes.fill.value == "transparent") {
        // add to db recipeId
        btn.title = "remove from saved";
        btn.querySelector("svg").attributes.fill.value = "currentColor";
    }
    else {

        btn.title = "save";
        btn.querySelector("svg").attributes.fill.value = "transparent";
        // rm from db recipeId
    }
}

function deleteRecipe(recipeId, elementId) {
    const recipeCard = document.getElementById(elementId);
    if (recipeCard) {
        recipeCard.remove();
        // history.back();
    }
}