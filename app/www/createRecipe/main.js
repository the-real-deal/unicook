import { rejectApiError } from "/js/errors.js"

const titleInput = document.getElementById("title")
const descriptionInput = document.getElementById("description")
const difficultyInput = document.getElementById("difficulty")
const prepTimeInput = document.getElementById("prepTime")
const costInput = document.getElementById("cost")
const servingsInput = document.getElementById("servings")
const backButton = document.getElementById("back-button")

backButton.addEventListener("click", (e) => {
    e.preventDefault()
    navigateBack()
    return false
})

descriptionInput.oninput = function () {
    this.style.height = ""
    this.style.height = (this.scrollHeight + 3) + 'px'
}

async function getRecipe() {
    const params = new URLSearchParams(document.location.search)
    const recipeId = params.get("recipeId")
    if (recipeId === null) {
        return null
    }
    const recipe = await fetch(`/api/recipes/data.php?recipeId=${recipeId}`)
        .then(rejectApiError)
        .then(res => res.json())

    const tags = await fetch(`/api/recipes/tags.php?recipeId=${recipeId}`)
        .then(rejectApiError)
        .then(res => res.json())

    const ingredients = await fetch(`/api/recipes/ingredients.php?recipeId=${recipeId}`)
        .then(rejectApiError)
        .then(res => res.json())

    const steps = await fetch(`/api/recipes/steps.php?recipeId=${recipeId}`)
        .then(rejectApiError)
        .then(res => res.json())
    return { ...recipe, ...{ tags, ingredients, steps } }
}

const recipe = await getRecipe()

let newIngredientIndex = 1
function addIngredientSlot(ingredient = null) {
    const ingredientsList = document.getElementById('ingredients')
    newIngredientIndex++ // TODO: gradle check, pianini ti osserva
    const li = document.createElement('li')
    li.classList.add('d-flex', 'justify-content-between', 'mb-2', 'align-items-center', 'gap-2')

    const labelQuantity = document.createElement('label')
    labelQuantity.setAttribute('for', `ingredientQuantity-${newIngredientIndex}`)
    labelQuantity.className = 'd-none'
    labelQuantity.hidden = true
    labelQuantity.textContent = 'enter the quantity of the next ingredient'

    const inputQuantity = document.createElement('input')
    inputQuantity.id = `ingredientQuantity-${newIngredientIndex}`
    inputQuantity.type = 'text'
    inputQuantity.name = 'ingredientsQuantity[]'
    inputQuantity.minLength = 1
    inputQuantity.maxLength = 30
    inputQuantity.className = 'p-2 col-md-3 col-2'
    inputQuantity.placeholder = 'Quantity'
    if (ingredient !== null) {
        inputQuantity.value = ingredient.quantity
    }

    const labelName = document.createElement('label')
    labelName.setAttribute('for', `ingredientName-${newIngredientIndex}`)
    labelName.className = 'd-none'
    labelName.hidden = true
    labelName.textContent = 'enter the next ingredient'

    const inputName = document.createElement('input')
    inputName.id = `ingredientName-${newIngredientIndex}`
    inputName.type = 'text'
    inputName.name = 'ingredientsName[]'
    inputName.minLength = 1
    inputName.maxLength = 30
    inputName.className = 'p-2 flex-grow-1'
    inputName.placeholder = 'Add ingredient'
    if (ingredient !== null) {
        inputName.value = ingredient.name
    }

    const button = document.createElement('button')
    button.type = 'button'
    button.className = 'p-2 flex-shrink-0'
    button.innerHTML = '&#128473;'
    button.onclick = function () {
        const children = ingredientsList.getElementsByTagName("li")
        if (children.length > 1) {
            this.parentElement.remove()
        }
    }

    li.appendChild(labelQuantity)
    li.appendChild(inputQuantity)
    li.appendChild(labelName)
    li.appendChild(inputName)
    li.appendChild(button)
    ingredientsList.appendChild(li)
}

function addTag(tag = null) {
    const select = document.getElementById('tags')
    let oldSelectValue = undefined
    if (tag !== null) {
        oldSelectValue = select.value
        select.value = tag.id
    }
    const selectedValue = select.value
    const selectedText = select.options[select.selectedIndex].text
    if (oldSelectValue !== undefined) {
        select.value = oldSelectValue
    }

    const tagList = document.getElementById('tag-list')

    const existingTags = Array.from(tagList.querySelectorAll('input[type="hidden"]')).map(input =>
        input.value
    )

    if (!existingTags.includes(selectedValue)) {
        const li = document.createElement('li')
        li.style.marginBottom = '5px'

        const textNode = document.createTextNode(selectedText + ' ')

        const button = document.createElement('button')
        button.type = 'button'
        button.innerHTML = '&#128473;'
        button.onclick = function () {
            removeTag(this)
        }

        const hiddenInput = document.createElement('input')
        hiddenInput.type = 'hidden'
        hiddenInput.name = 'tagIds[]'
        hiddenInput.value = selectedValue

        li.appendChild(textNode)
        li.appendChild(button)
        li.appendChild(hiddenInput)

        tagList.appendChild(li)
    }

    select.selectedIndex = 0
}

function removeTag(button) {
    button.parentElement.remove()
}

let newStepIndex = 1
function addStepSlot(step = null) {
    const stepsList = document.getElementById('steps')
    const newIndex = newStepIndex++
    const li = document.createElement('li')
    li.classList.add('d-flex', 'justify-content-between', 'mb-2', 'align-items-center', 'gap-2')

    const labelStep = document.createElement('label')
    labelStep.setAttribute('for', `step-${newIndex}`)
    labelStep.className = 'd-none'
    labelStep.hidden = true
    labelStep.textContent = 'enter the recipe step'

    const inputStep = document.createElement('textarea')
    inputStep.oninput = function () {
        this.style.height = ""
        this.style.height = (this.scrollHeight + 3) + 'px'
    }
    inputStep.id = `step-${newIndex}`
    inputStep.name = 'steps[]'
    inputStep.className = 'p-2 flex-grow-1'
    inputStep.placeholder = 'Describe the step'
    if (step !== null) {
        inputStep.value = step.instruction
    }

    const button = document.createElement('button')
    button.type = 'button'
    button.className = 'p-2 flex-shrink-0'
    button.innerHTML = '&#128473;'
    button.onclick = function () {
        const children = stepsList.getElementsByTagName("li")
        if (children.length > 1) {
            this.parentElement.remove()
        }
    }

    li.appendChild(labelStep)
    li.appendChild(inputStep)
    li.appendChild(button)
    stepsList.appendChild(li)
}

document.getElementById('add-ingredients-slot').addEventListener('click', () => addIngredientSlot())
document.getElementById('add-step-slot').addEventListener('click', () => addStepSlot())
document.getElementById('tags').addEventListener('change', () => addTag())

const form = document.getElementById("recipeForm")


form.addEventListener("submit", async (e) => {
    e.preventDefault()

    const data = new FormData(form)
    if (recipe !== null) {
        data.set("recipeId", recipe.id)
    }

    const res = await fetch(form.action, {
        method: form.method,
        body: data,
    }).then(rejectApiError)
        .then(r => r.json())

    window.location = `/singleRecipe?recipeId=${recipe === null ? res.id : recipe.id}`
})

if (recipe !== null) {
    titleInput.value = recipe.title
    descriptionInput.value = recipe.description
    difficultyInput.value = recipe.difficulty
    prepTimeInput.value = recipe.prepTime
    costInput.value = recipe.cost
    servingsInput.value = recipe.servings
    recipe.tags.forEach(addTag)
    recipe.ingredients.forEach(addIngredientSlot)
    recipe.steps.forEach(addStepSlot)
} else {
    addIngredientSlot()
    addStepSlot()
}