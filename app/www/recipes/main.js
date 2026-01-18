document.getElementById('clickme').addEventListener('click', () => { addRecipeFromTemplate() });

document.getElementById('search-form').addEventListener('change', (e) => {
    console.log(e.target.name);
    console.log('search-bar');
    console.log(e.target.name != 'search-bar');
    if (e.target.name != 'search-bar')
        console.log('Form changed!', e.target.name, e.target.value);
});

document.getElementById('search-recipes').addEventListener('keyup', (e) => {
    console.log('Form changed!', e.target.name, e.target.value);
});

let index = 0;

let recipeData = {
    'recipeID': '1',
    'recipeTitle': 'Recipe Title',
    'tags': ["Tag#1", "Tag#2", "Tag#3"],
    'timeRequired': 20,
    'cost': 'cheap',
    'saved': true
}

function addRecipeFromTemplate() {
    const template = document.getElementById("{template}");
    const clone = template.cloneNode(true);

    const newID = "recipe-" + index++;

    clone.id = newID;

    // clone.querySelector('img').src = "link";

    const title = clone.querySelector('h3');
    title.textContent = recipeData.recipeTitle;
    title.setAttribute('data-recipe-id', `${recipeData.recipeID}`);
    title.addEventListener('click', () => {
        changePage(recipeData.recipeID);
    })

    const tags = clone.querySelector('ul');

    recipeData.tags.forEach(element => {
        const li = document.createElement('li');
        li.textContent = element;
        tags.appendChild(li);
    });

    const link = clone.querySelector('a');
    link.setAttribute('href', `/singleRecipe?id=${recipeData.recipeID}`);

    const button = clone.querySelector('button');
    if (button) {
        button.id = "btn-" + newID;
        button.setAttribute('onclick', `saveRecipe('${"btn-" + newID}', '${recipeData.recipeID}')`);

        if (recipeData.saved) {
            clone.querySelector('button>svg').setAttribute('fill', 'currentColor');
        }
    }

    clone.querySelector('.pe-3:first-of-type span').textContent = recipeData.timeRequired + " min";
    clone.querySelector('.pe-3:last-of-type span').textContent = recipeData.cost;


    document.getElementById('recipe-container').appendChild(clone);
}