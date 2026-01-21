import { rejectApiError } from "/js/errors.js"

document.querySelectorAll('.review').forEach(reviewElement => {
    const deleteButton = reviewElement.querySelector('button')
    deleteButton?.addEventListener('click', async () => {
        const reviewId = reviewElement.dataset.reviewid
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
})