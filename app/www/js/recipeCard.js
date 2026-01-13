function saveRecipe(btnId, recipeId) {

    const btn = document.getElementById(btnId);
    console.log(btn.querySelector("svg").attributes.fill.value == "transparent");
    if (btn.querySelector("svg").attributes.fill.value == "transparent") {
        // add to db recipeId
        btn.style.color = "var(--primary)";
        btn.querySelector("svg").attributes.fill.value = "currentColor";
    }
    else {
        btn.style.color = "var(--text)";
        btn.querySelector("svg").attributes.fill.value = "transparent";
        // rm from db recipeId
    }
}

function deleteRecipe(id) {
    const card = document.getElementById(id).parentNode();
}