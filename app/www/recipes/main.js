import { rejectApiError } from "/js/errors.js"

const form = document.getElementById('search-form');
let nRecipes = 0;
let lastBatchCount = -1;
const nextBatchCount = 8;
let timerID = null;

document.getElementById('clickme').addEventListener('click', () => { addRecipeFromTemplate() });

form.addEventListener('change', (e) => {
    if (e.target.name != 'search-bar')
        handleFormChange(e);
});

document.getElementById('search-recipes').addEventListener('keyup', (e) => {
    if (timerID)
        clearTimeout(timerID);

    timerID = setTimeout(() => {
        handleFormChange(e);
        timerID = null;
    }, 1000);
});

async function handleFormChange(event) {
    removeAllChilds();
    const data = new FormData(form);
    const dataSent = new URLSearchParams();

    const time = data.get('time');
    data.delete('time');
    switch (time) {
        case '0':
            data.append('maxPrepTime', 30);
            break;
        case '1':
            data.append('minPrepTime', 30);
            data.append('maxPrepTime', 60);
            break;
        case '2':
            data.append('minPrepTime', 30);
            break;
        default:
            break;
    }

    data.forEach((v, k) => {
        dataSent.append(k, v);
    });

    const response = await fetch(form.action + dataSent.toString(), {
        method: form.method,
    }).then(rejectApiError)

    if (res.ok) {
        console.log(res);
    }
}


let index = 0;

let recipeData = {
    'recipeID': '1',
    'recipeTitle': 'Recipe Title',
    'tags': ["Tag#1", "Tag#2", "Tag#3"],
    'timeRequired': 20,
    'cost': 'cheap',
    'saved': false
}

function addRecipeFromTemplate() {
    const template = document.getElementById("{template}");
    const clone = template.cloneNode(true);

    const newID = "recipe-" + index++;

    clone.id = newID;

    clone.querySelector('img').src = `/api/recipes/image.php?recipeId=${recipeData.id}`;

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

function removeAllChilds() {
    document.getElementById('recipe-container').innerHTML = '';
    nRecipes = 0;
    lastBatchCount = -1;
}