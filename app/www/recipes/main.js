import { rejectApiError } from "/js/errors.js"

const form = document.getElementById('search-form')
const counter = document.getElementById('recipeCount')
const nextBatchCount = 4
let timerID = null
var recipeIndex = 0
var index = 0
var lastBatchCount = -1
var requestingNext = false

window.onload = function () {
    updateRecipes()
}

window.addEventListener('scroll', function () {
    if (window.innerHeight + window.scrollY >= document.body.scrollHeight * 0.9) {
        if (!requestingNext) {
            requestingNext = true
            if (lastBatchCount == nextBatchCount) {
                console.log('updating')
                updateRecipes()
            }
        }
    }
})

form.addEventListener('change', (e) => {
    if (e.target.name != 'search-bar') {
        removeAllChilds()
        updateRecipes()
    }
})

form.addEventListener('submit', (e) => {
    e.preventDefault()
}

)

document.getElementById('search-recipes').addEventListener('keyup', (e) => {
    if (timerID)
        clearTimeout(timerID)
    timerID = setTimeout(() => {
        removeAllChilds()
        updateRecipes()
        timerID = null
    }, 1000)
})

async function updateRecipes() {
    const data = new FormData(form)
    const dataSent = new URLSearchParams()

    const time = data.get('time')
    data.delete('time')
    switch (time) {
        case '0':
            data.append('maxPrepTime', 30)
            break
        case '1':
            data.append('minPrepTime', 30)
            data.append('maxPrepTime', 60)
            break
        case '2':
            data.append('minPrepTime', 30)
            break
        default:
            break
    }

    data.forEach((v, k) => {
        dataSent.append(k, v)
    })

    dataSent.append('from', index)
    dataSent.append('n', nextBatchCount)

    const response = await fetch(form.action + dataSent.toString(), {
        method: form.method,
    }).then(rejectApiError)

    if (response.ok) {
        const resArray = await response.json()
        lastBatchCount = resArray.length
        index += lastBatchCount
        counter.textContent = index
        resArray.forEach(json => {
            addRecipeFromTemplate(json)
        })
    }
}

async function addRecipeFromTemplate(recipeData) {
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
        button.setAttribute('onclick', `saveRecipe('${"btn-" + newID}', '${recipeData.id}')`)

        const savedRes = await fetch(`/api/recipes/saved.php?recipeId=${recipeData.id}`,
            { method: "GET" }).then(rejectApiError).then(r => r.json())

        if (savedRes.saved) {
            clone.querySelector('button>svg').setAttribute('fill', 'currentColor')
        }
    }

    clone.querySelector('.pe-3:first-of-type span').textContent = recipeData.prepTime + " min"
    clone.querySelector('.pe-3:last-of-type span').textContent = costEnumToString(recipeData.cost)

    document.getElementById('recipe-container').appendChild(clone)
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
    document.getElementById('recipe-container').innerHTML = ''
    index = 0
    lastBatchCount = -1
}