import { rejectApiError } from "/js/errors.js"
import { changePage, saveRecipe } from "/js/recipeCard.js"

const form = document.getElementById('search-form')
const counter = document.getElementById('recipeCount')
const searchBar = document.getElementById('search-recipes')
const recipeContainer = document.getElementById('recipe-container')

let nextBatchCount
const width = window.innerWidth
// https://getbootstrap.com/docs/5.3/layout/breakpoints/#available-breakpoints
const breakpoints = {
    xxl: 1400,
    xl: 1200,
    lg: 992,
    md: 768,
    sm: 576,
}
if (width >= breakpoints.xxl) nextBatchCount = 8
else if (width >= breakpoints.xl) nextBatchCount = 8
else if (width >= breakpoints.lg) nextBatchCount = 8
else if (width >= breakpoints.md) nextBatchCount = 4
else if (width >= breakpoints.sm) nextBatchCount = 2
else nextBatchCount = 2

let timerID = null
let recipeIndex = 0
let index = 0
let lastBatchCount = -1
let requestingNext = false

window.addEventListener('scroll', async function () {
    if (
        !requestingNext &&
        window.innerHeight + window.scrollY >= (document.body.scrollHeight / 2)
    ) {
        requestingNext = true
        if (lastBatchCount == nextBatchCount) {
            await updateRecipes({ remove: false, ajax: true })
        }
    }
})

form.addEventListener('change', async (e) => {
    if (e.target === searchBar) {
        return
    }
    await updateRecipes()
})

form.addEventListener('submit', async (e) => {
    e.preventDefault()
    if (timerID) {
        clearTimeout(timerID)
    }
    await updateRecipes()
})

searchBar.addEventListener('keyup', (e) => {
    if (timerID) {
        clearTimeout(timerID)
    }
    timerID = setTimeout(async () => {
        timerID = null
        await updateRecipes()
    }, 200)
})

async function updateRecipes({
    remove = true,
    ajax = false
} = {}) {
    const data = new FormData(form)
    const dataSent = new URLSearchParams()

    const time = data.get('time')
    data.delete('time')
    switch (time) {
        case '0':
            data.append('maxPrepTime', 30)
            break
        case '1':
            data.append('minPrepTime', 31)
            data.append('maxPrepTime', 60)
            break
        case '2':
            data.append('minPrepTime', 61)
            break
        default:
            break
    }

    data.forEach((v, k) => {
        dataSent.append(k, v)
    })

    if (!ajax) {
        index = 0
    }

    dataSent.append('from', index)
    dataSent.append('n', nextBatchCount)

    const response = await fetch(form.action + dataSent.toString(), {
        method: form.method,
    }).then(rejectApiError)
        .then(r => r.json())

    const fragment = document.createDocumentFragment()
    for (const recipeData of response) {
        await addRecipeFromTemplate(fragment, recipeData)
    }
    if (remove) {
        removeAllChilds()
    }
    lastBatchCount = response.length
    index += lastBatchCount
    counter.textContent = index
    recipeContainer.appendChild(fragment)
    requestingNext = false
}

async function addRecipeFromTemplate(fragment, recipeData) {
    const template = document.getElementById("{template}")
    const clone = template.cloneNode(true)

    const newID = "recipe-" + recipeIndex++

    clone.id = newID

    clone.querySelector('img').src = `/api/recipes/image.php?recipeId=${recipeData.id}`

    const title = clone.querySelector('h3')
    title.textContent = recipeData.title
    title.setAttribute('data-recipe-id', `${recipeData.id}`)
    title.addEventListener('click', () => {
        changePage(recipeData.id)
    })

    const tags = clone.querySelector('ul')

    const res = await fetch(`/api/recipes/tags.php?recipeId=${recipeData.id}`,
        { method: "GET" }).then(rejectApiError)

    const tagsResponse = await res.json()

    tagsResponse.forEach(element => {
        const li = document.createElement('li')
        li.textContent = element.name
        tags.appendChild(li)
    })

    const link = clone.querySelector('a')
    link.setAttribute('href', `/singleRecipe?recipeId=${recipeData.id}`)

    const button = clone.querySelector('button')
    if (button) {
        button.id = "btn-" + newID
        button.addEventListener("click", async (e) => {
            await saveRecipe("btn-" + newID, recipeData.id)
        })

        const savedRes = await fetch(`/api/recipes/saved.php?recipeId=${recipeData.id}`,
            { method: "GET" }).then(rejectApiError).then(r => r.json())

        if (savedRes.saved) {
            clone.querySelector('button>svg').setAttribute('fill', 'currentColor')
        }
    }

    clone.querySelector('.pe-3:first-of-type span').textContent = recipeData.prepTime + " min"
    clone.querySelector('.pe-3:last-of-type span').textContent = costEnumToString(recipeData.cost)

    fragment.appendChild(clone)
}

function costEnumToString(cost) {
    switch (cost) {
        case 0:
            return "Cheap"
        case 1:
            return "Medium"
        case 2:
            return "Expensive"
        default:
            break
    }
}

function removeAllChilds() {
    recipeContainer.innerHTML = ''
    index = 0
    lastBatchCount = -1
}

await updateRecipes({ remove: false })