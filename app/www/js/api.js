export class ApiError extends Error { }

export function rejectApiError(res) {
  if (res.ok) {
    return res
  }
  return res.json().then(json => {
    throw new ApiError(json.error)
  })
}
