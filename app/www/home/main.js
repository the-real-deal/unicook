import { rejectApiError } from "/js/api.js"

const form = document.getElementById("testForm")
const img = document.getElementById("uploadedImage")
const code = document.getElementById("uploadedImageMetadata")

form.addEventListener("submit", async (e) => {
  console.log("Submit handler called")
  e.preventDefault() // prevent opening of new page

  const result = await fetch(form.action, {
    method: form.method,
    body: new FormData(form),
  })
    .then(rejectApiError)
    .then((res) => res.json())
    .catch((err) => {
      code.innerText = `${err}`
      return null
    })
  if (result === null) {
    return
  }
  code.innerText = JSON.stringify(result, null, 2)
  img.src = `/api/files/content.php?id=${result.id}`
})
