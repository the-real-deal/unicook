import { rejectApiError } from "/js/errors.js"

let newIngredientIndex = 2
function addIngredientSlot() {
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
    inputQuantity.className = 'p-2 col-md-2'
    inputQuantity.placeholder = 'Quantity'

    const labelName = document.createElement('label')
    labelName.setAttribute('for', `ingredientName-${newIngredientIndex}`)
    labelName.className = 'd-none'
    labelName.hidden = true
    labelName.textContent = 'enter the next ingredient'

    const inputName = document.createElement('input')
    inputName.id = `ingredientName-${newIngredientIndex}`
    inputName.type = 'text'
    inputName.name = 'ingredientsName[]'
    inputName.className = 'p-2 flex-grow-1'
    inputName.placeholder = 'Add ingredient'

    const button = document.createElement('button')
    button.type = 'button'
    button.className = 'p-2 flex-shrink-0'
    button.innerHTML = '&#128473;'
    button.onclick = function () {
        this.parentElement.remove()
    }

    li.appendChild(labelQuantity)
    li.appendChild(inputQuantity)
    li.appendChild(labelName)
    li.appendChild(inputName)
    li.appendChild(button)
    ingredientsList.appendChild(li)
}

function addTag() {
    const select = document.getElementById('tags')
    const selectedValue = select.value
    const selectedText = select.options[select.selectedIndex].text
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
        hiddenInput.name = 'tags[]'
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

let newStepIndex = 2
function addStepSlot() {
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
    inputStep.style.resize = 'none'
    inputStep.id = `step-${newIndex}`
    inputStep.name = 'steps[]'
    inputStep.className = 'p-2 flex-grow-1'
    inputStep.placeholder = 'Describe the step'

    const button = document.createElement('button')
    button.type = 'button'
    button.className = 'p-2 flex-shrink-0'
    button.innerHTML = '&#128473;'
    button.onclick = function () {
        this.parentElement.remove()
    }

    li.appendChild(labelStep)
    li.appendChild(inputStep)
    li.appendChild(button)
    stepsList.appendChild(li)
}

document.getElementById('add-ingredients-slot').addEventListener('click', addIngredientSlot)
addIngredientSlot()
document.getElementById('add-step-slot').addEventListener('click', addStepSlot)
addStepSlot()
document.getElementById('tags').addEventListener('change', addTag)

const form = document.getElementById("recipeForm")


form.addEventListener("submit", async (e) => {
    e.preventDefault()

    const data = new FormData(form)

    const res = await fetch(form.action, {
        method: form.method,
        body: data,
    }).then(rejectApiError)

    window.location = "/singleRecipe"
})