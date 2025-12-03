/// <reference path="../types.js" />

const form = /** @type {HTMLFormElement | null} */(document.getElementById("testForm"))
const img = /** @type {HTMLImageElement | null} */(document.getElementById("uploadedImage"))
const code = /** @type {HTMLElement | null} */(document.getElementById("uploadedImageMetadata"))

form?.addEventListener("submit", async (e) => {
    e.preventDefault() // prevent opening of new page
    const result = /** @type {InputFileResponse} */ (await fetch(form.action, { method: form.method })
        .then((res) => res.json()))

})
