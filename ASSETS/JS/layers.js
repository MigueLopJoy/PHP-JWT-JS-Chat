import { renderUserContacts } from "./contacts.js"
import { printErrorMessage } from "./alerts.js"

const d = document,
    authenticationContainer = d.querySelector(".authentication-container"),
    contactsContainer = d.querySelector(".contacts-container"),
    chatContainer = d.querySelector(".chat-container"),
    loginContainer = d.querySelector(".login-container"),
    registerContainer = d.querySelector(".register-container"),
    chatWindow = d.querySelector(".chat-window")

const showLoginAuthenticationError = ex => {
    showLoginPage()
    if (ex) printErrorMessage(ex)
}

const showLoginPage = () => {
    showPage(authenticationContainer)
    hidePage(contactsContainer)
    showPage(loginContainer)
    hidePage(registerContainer)
}

const showRegisterPage = () => {
    showPage(registerContainer)
    hidePage(loginContainer)
}

const showContactsPage = () => {
    showPage(contactsContainer)
    hidePage(authenticationContainer)
    renderUserContacts()
    setInterval(async () => {
        renderUserContacts()
    }, 5000)
}

const showChatPage = () => {
    showPage(chatContainer)
    hidePage(contactsContainer)
}

const showPage = page => {
    if (page.classList.contains("d-none")) {
        page.classList.remove("d-none")
    }
}

const hidePage = page => {
    if (!page.classList.contains("d-none")) {
        page.classList.add("d-none")
    }
}

const scrollToBottom = () => {
    chatWindow.scrollTop = chatWindow.firstElementChild.offsetHeight
}

export {
    showLoginAuthenticationError,
    showContactsPage,
    showChatPage,
    showRegisterPage,
    showLoginPage,
    scrollToBottom
}