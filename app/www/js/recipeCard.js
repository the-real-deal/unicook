function saveRecipe(id) {

    const btn = document.getElementById(id);
    if (btn.querySelector("svg").attributes.fill.value == "transparent") {
        // add to db
        btn.querySelector("svg").attributes.fill.value = "currentColor";
        btn.style.color = "var(--primary)";
    }
    else {
        btn.querySelector("svg").attributes.fill.value = "transparent";
        btn.style.color = "var(--text)";
        // rm from db
    }
}