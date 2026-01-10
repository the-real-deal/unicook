let currentResponse = ''
const form = document.getElementById('chatForm')
const prompt = document.getElementById('prompt')
const responseDiv = document.getElementById('response')
const statusDiv = document.getElementById('status')
const sendBtn = document.getElementById('sendBtn')

// Allow Enter key to send (Shift+Enter for new line)
prompt.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault()
        form.requestSubmit()
    }
})

form.addEventListener("submit", async (e) => {
    e.preventDefault()

    // Reset
    currentResponse = ''
    responseDiv.textContent = ''
    statusDiv.textContent = 'Connecting...'
    sendBtn.disabled = true

    try {
        const response = await fetch(form.action, {
            method: form.method,
            body: new FormData(form)
        })

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }

        statusDiv.textContent = 'Streaming response...'
        const reader = response.body.getReader()
        const decoder = new TextDecoder()

        let displayedText = ''
        let pendingText = ''
        let isAnimating = false

        // Smooth character-by-character animation
        const animateText = () => {
            if (pendingText.length > 0) {
                // Add characters gradually (adjust speed here)
                const charsToAdd = Math.min(3, pendingText.length) // Add 3 chars at a time
                displayedText += pendingText.substring(0, charsToAdd)
                pendingText = pendingText.substring(charsToAdd)

                responseDiv.textContent = displayedText

                // Auto-scroll the entire page to bottom
                window.scrollTo(0, document.body.scrollHeight)

                // Continue animation
                requestAnimationFrame(animateText)
            } else {
                isAnimating = false
            }
        }

        const addToPending = (text) => {
            pendingText += text
            if (!isAnimating) {
                isAnimating = true
                requestAnimationFrame(animateText)
            }
        }

        while (true) {
            const { done, value } = await reader.read()

            if (done) {
                // Wait for animation to finish
                while (pendingText.length > 0) {
                    await new Promise(resolve => setTimeout(resolve, 16))
                }
                statusDiv.textContent = 'Complete!'
                break
            }

            const chunk = decoder.decode(value, { stream: true })
            currentResponse += chunk
            addToPending(chunk)
        }

    } catch (error) {
        console.error('Error:', error)
        statusDiv.textContent = `Error: ${error.message}`
    } finally {
        sendBtn.disabled = false
    }
})

// Focus textarea on load
prompt.focus()