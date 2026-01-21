import { rejectApiError } from "/js/errors.js"

export function hydrateReviewElement(reviewElement) {
    const deleteButton = reviewElement.querySelector('button')
    const reviewId = reviewElement.dataset.reviewid

    deleteButton?.addEventListener('click', async () => {
        const data = new FormData()
        data.set('reviewId', reviewId)
        const response = await fetch('/api/reviews/delete.php', {
            method: 'POST',
            body: data
        }).then(rejectApiError)

        if (response.ok) {
            reviewElement.remove()
        }
    })
}

document.querySelectorAll('.review').forEach(hydrateReviewElement)