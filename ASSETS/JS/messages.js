import { printErrorMessage } from "./alerts.js"
import { fetchHandler, getRequestData } from "./fetch.js"
import { scrollToBottom } from "./layers.js"

const d = document,
    chatForm = d.querySelector(".form.chat-form"),
    chatWindow = d.querySelector(".chat-window"),
    messagesContainer = d.querySelector(".messages-container")

let conversationLength = 0

const getConversation = async contactPhoneNumber => {
    let url = `./ASSETS/PHP/CONTROLLERS/front-controller.php?get-conversation=${contactPhoneNumber}`,
        jwt = localStorage.getItem('jwt')

    return fetchHandler(url, getRequestData("GET", jwt))
        .then(res => res.conversation)
        .catch(ex => printErrorMessage(ex.error))
}


const renderConversation = async contactPhoneNumber => {
    return getConversation(contactPhoneNumber)
        .then(conversation => {
            for (let i = 0; i < conversation.length; i++) {
                let message = conversation[i]
                if (i >= conversationLength) {
                    renderMessage(message)
                }
            }
            conversationLength = conversation.length
            return true
        })
        .catch(ex => printErrorMessage(ex.error))
}

const conversationLoop = contactPhoneNumber => {
    renderConversation(contactPhoneNumber)
        .then(res => {
            if (res) {
                setInterval(() => {
                    renderConversation(contactPhoneNumber)
                        .then(res => {
                            if (res) {
                                scrollToBottom()
                            }
                        })
                }, 1500)
            }
        })
}

const sendMessage = () => {
    let url = `./ASSETS/PHP/CONTROLLERS/front-controller.php?send-message`,
        jwt = localStorage.getItem('jwt')

    fetchHandler(url, getRequestData("POST", jwt, getMessageRequestBody()))
        .then(res => {
            chatForm.reset()
            renderMessage(res)
            conversationLength++
            scrollToBottom()
        })
        .catch(ex => printErrorMessage(ex.error))
}

const renderMessage = message => {
    messagesContainer.innerHTML += `
    <div class="message-box">
        <div class="message-info">
            <div class="user">
                <p><b>${message.firstname} ${message.surname}</b></p>
            </div>
            <div class="date">
                <p><b>${message.dateTime}</b></p>
            </div>
        </div>
        <div class="message-text">
            <p>${message.message}</p>
        </div>
    </div>`
}

const getMessageRequestBody = () => {
    return JSON.stringify({
        contactPhoneNumber: chatWindow.getAttribute("data_contactPhoneNumber"),
        message: chatForm.newMessage.value
    })
}

export {
    renderConversation,
    sendMessage,
    conversationLoop
}