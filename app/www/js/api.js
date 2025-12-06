class ApiError extends Error {}

/**
 * @param {Response} res
 * @returns {Response}
 */
function rejectApiError(res) {
  if (res.ok) {
    return res;
  }
  throw new ApiError(res.json().error);
}
