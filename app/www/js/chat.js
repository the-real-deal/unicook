import { marked } from '/js/marked.js'
import { rejectApiError } from '/js/errors.js'

const chatOpenButton = document.getElementById('chat-open-button')
const chatContent = document.getElementById('chat-content')
const messageBox = document.getElementById('message-box')
const messageTextArea = document.getElementById('message-text-area')
const messageSendButton = document.getElementById('message-send-button')
const statusDiv = document.getElementById('status')
const clearChatButton = document.getElementById('clear-chat')

//chat --> responseDiv

let responseDiv = null

function createMessageDiv(role) {
    const messageDiv = document.createElement('div')
    const messageText = document.createElement('p')
    messageText.classList.add('py-2', 'px-3', 'my-2')

    if (role === 'user') {
        messageDiv.classList.add('user', 'd-flex', 'justify-content-end', 'w-100', 'pe-3')
    } else if (role === 'assistant') {
        messageDiv.classList.add('assistant', 'd-flex', 'w-100', 'ps-3')

    }
    messageDiv.appendChild(messageText)
    chatContent.appendChild(messageDiv)
    if (role === 'assistant') {
        responseDiv = messageText
    }
    return messageText
}

function uploadChat(messages) {
    if (!chatContent) return
    chatContent.innerHTML = ''

    for (const message of messages) {
        const messageText = createMessageDiv(message.role)
        messageText.innerHTML = marked.parse(message.content)
    }
}

// Allow Enter key to send (Shift+Enter for new line)
messageTextArea.addEventListener("keydown", function (e) {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault()
        messageBox.requestSubmit()
    }
})

function displayResponse(displayedText) {
    let markdownHtml = marked.parse(displayedText)
    responseDiv.innerHTML = markdownHtml
}

messageBox.addEventListener('submit', async (e) => {
    e.preventDefault()
    // Reset
    const userMessageDiv = createMessageDiv('user')
    userMessageDiv.innerHTML = marked.parse(messageTextArea.value)
    messageSendButton.disabled = true
    let dataFinished = false
    let dotInterval = null
    let displayedText = "" // accumulated displayed text

    try {
        const data = new FormData(messageBox)
        const response = await fetch(messageBox.action, {
            method: messageBox.method,
            body: data
        }).then(rejectApiError)
            .catch(err => {
                userMessageDiv.remove()
                return Promise.reject(err)
            })

        messageTextArea.value = ''
        createMessageDiv('assistant')
        messageBox.reset()


        statusDiv.textContent = "Writing..."
        const reader = response.body.getReader()
        const decoder = new TextDecoder()

        let pendingText = ""
        let isAnimating = false
        let dotsCount = 0

        // Smooth character-by-character animation
        function animateText() {
            if (pendingText.length > 0) {
                dotsCount = 0
                clearInterval(dotInterval)
                // Add characters gradually (adjust speed here)
                // const charsToAdd = Math.min(3, pendingText.length) // Add 3 chars at a time
                const charsToAdd = 1
                displayedText += pendingText.substring(0, charsToAdd)
                pendingText = pendingText.substring(charsToAdd)

                displayResponse(displayedText)

                chatContent.scrollTop = chatContent.scrollHeight

                // Continue animation
                requestAnimationFrame(animateText)
            } else {
                isAnimating = false
                if (dataFinished) {
                    dotsCount = 0
                    clearInterval(dotInterval)
                    displayResponse(displayedText)
                    statusDiv.textContent = "Available"
                    messageSendButton.disabled = false
                    return
                }
                dotInterval = setInterval(() => {
                    dotsCount = (dotsCount + 1) % 4
                    displayResponse(displayedText + ".".repeat(dotsCount))
                }, 200)
            }
        }

        function addToPending(text) {
            pendingText += text
            if (!isAnimating) {
                isAnimating = true
                requestAnimationFrame(animateText)
            }
        }

        while (true) {
            const { done, value } = await reader.read()
            console.log(done, value)

            if (done) {
                break
            }

            const chunk = decoder.decode(value, { stream: true })
            addToPending(chunk)
        }
        dataFinished = true
    } catch (error) {
        console.error("Error:", error)
        statusDiv.textContent = "Available"
        messageSendButton.disabled = false
    } finally {
        dataFinished = true
        clearInterval(dotInterval)
    }
})

chatOpenButton.addEventListener('click', async () => {
    const response = await fetch('/api/chat/messages.php', {
        method: 'GET',
    }).then(rejectApiError)

    if (response.ok) {
        const messages = await response.json()
        uploadChat(messages)
    }
})

clearChatButton.addEventListener('click', async () => {
    const response = await fetch('/api/chat/clear.php', {
        method: 'POST',
    }).then(rejectApiError)

    if (response.ok) {
        chatContent.innerHTML = ''
    }
})