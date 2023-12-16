import {
    sendMessage
} from "./messages.js"

import {
    searchContact,
    openContactChat,
    renderUserContacts
} from "./contacts.js"

import {
    checkAuthenticationAndLoadInterface,
    loginUser,
    registerUser,
    logout
} from "./authentication.js"

import { showRegisterPage } from "./layers.js"

const d = document,
    loginForm = d.querySelector(".form.login-form"),
    registerForm = d.querySelector(".form.register-form"),
    chatContainer = d.querySelector(".chat-container"),
    chatForm = d.querySelector(".form.chat-form"),
    contactsContainer = d.querySelector(".contacts-container"),
    closeChatWindow = d.querySelector(".close-chat-btn span"),
    contactsForm = d.querySelector(".contacts-form"),
    openRegisterBtn = d.querySelector(".open-register-btn"),
    logoutBtn = d.querySelector(".logout-btn span")

d.addEventListener("DOMContentLoaded", () => {
    checkAuthenticationAndLoadInterface()
})

d.addEventListener("submit", e => {
    e.preventDefault()
    if (e.target === loginForm) {
        loginUser()
    } else if (e.target === registerForm) {
        registerUser()
    } else if (e.target === chatForm) {
        sendMessage()
    } else if (e.target === contactsForm) {
        searchContact()
    }
})

d.addEventListener("keyup", e => {
    if (e.target === chatForm && e.code === "Enter") {
        handleNewMessage()
    }
})

d.addEventListener("click", e => {
    if (e.target === closeChatWindow) {
        chatContainer.classList.toggle("d-none")
        contactsContainer.classList.toggle("d-none")
    } else if (e.target.matches(".contact-click")) {
        for (let i = 1; i <= d.querySelectorAll(".contact-click").length; i++) {
            if (e.target === d.querySelector(`.contact-${i}`)) {
                openContactChat(e.target)
            }
        }
    } else if (e.target === openRegisterBtn) {
        showRegisterPage()
    } else if (e.target === logoutBtn) {
        logout()
    } else if (e.target.matches(".view-all-btn > span")) {
        d.querySelector(".view-all-btn > span").classList.add("d-none")
        renderUserContacts()
    }
})