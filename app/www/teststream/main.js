import { marked } from "/js/marked.js"

let currentResponse = ""
const form = document.getElementById("chatForm")
const content = document.getElementById("content")
const chat = document.getElementById("chat")
const statusDiv = document.getElementById("status")
const sendBtn = document.getElementById("sendBtn")

// Allow Enter key to send (Shift+Enter for new line)
content.addEventListener("keydown", function (e) {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault()
        form.requestSubmit()
    }
})

function displayResponse(displayedText) {
    let markdownHtml = marked.parse(displayedText)
    chat.innerHTML = markdownHtml
}

form.addEventListener("submit", async (e) => {
    e.preventDefault()

    // Reset
    currentResponse = ""
    chat.textContent = ""
    statusDiv.textContent = "Connecting..."
    sendBtn.disabled = true
    let dataFinished = false
    let dotInterval = null
    let displayedText = ""

    try {
        const data = new FormData(form)
        const response = await fetch(form.action, {
            method: form.method,
            body: data
        })

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }

        statusDiv.textContent = "Streaming response..."
        const reader = response.body.getReader()
        const decoder = new TextDecoder()

        let pendingText = ""
        let isAnimating = false
        let dotsCount = 0

        // Smooth character-by-character animation
        const animateText = () => {
            if (pendingText.length > 0) {
                dotsCount = 0
                clearInterval(dotInterval)
                // Add characters gradually (adjust speed here)
                // const charsToAdd = Math.min(3, pendingText.length) // Add 3 chars at a time
                const charsToAdd = 1
                displayedText += pendingText.substring(0, charsToAdd)
                pendingText = pendingText.substring(charsToAdd)

                displayResponse(displayedText)

                // autoscroll?

                // Continue animation
                requestAnimationFrame(animateText)
            } else {
                isAnimating = false
                if (dataFinished) {
                    dotsCount = 0
                    clearInterval(dotInterval)
                    displayResponse(displayedText)
                    statusDiv.textContent = "Streaming complete!"
                    sendBtn.disabled = false
                    return
                }
                dotInterval = setInterval(() => {
                    dotsCount = (dotsCount + 1) % 4
                    displayResponse(displayedText + ".".repeat(dotsCount))
                }, 200)
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
                break
            }

            const chunk = decoder.decode(value, { stream: true })
            currentResponse += chunk
            addToPending(chunk)
        }
        dataFinished = true
    } catch (error) {
        console.error("Error:", error)
        statusDiv.textContent = `Error: ${error.message}`
        sendBtn.disabled = false
    } finally {
        dataFinished = true
        clearInterval(dotInterval)
        displayResponse(displayedText)
    }
})

// Focus textarea on load
prompt.focus()