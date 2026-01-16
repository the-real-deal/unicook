import bootstrap from "/js/bootstrap.js"

export class ApiError extends Error { }

export function showError(message) {
  const errorToast = document.getElementById("errorToast")
  if (errorToast) {
    const body = errorToast.querySelector(".toast-body")
    if (body) {
      body.innerText = message
      bootstrap.Toast.getOrCreateInstance(errorToast).show()
    }
  }
}

export function rejectApiError(res) {
  if (res.ok) {
    return res
  }
  return res.json().then(json => {
    const message = json.error
    showError(message)
    return Promise.reject(new ApiError(message))
  })
}
