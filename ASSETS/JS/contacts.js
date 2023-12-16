import {
    fetchHandler,
    getRequestData
} from "./fetch.js"

import {
    printErrorMessage
} from "./alerts.js"

import {
    showChatPage
} from "./layers.js"

import { conversationLoop } from "./messages.js"

const d = document,
    contactsForm = d.querySelector(".contacts-form"),
    contactsWindow = d.querySelector(".contacts-window"),
    chatWindow = d.querySelector(".chat-window")

let contactsNumber = 0

const getUserContacts = async () => {
    let url = `./ASSETS/PHP/CONTROLLERS/front-controller.php?get-contacts`,
        jwt = localStorage.getItem('jwt')

    return fetchHandler(url, getRequestData("GET", jwt))
        .then(res => res.contacts)
        .catch(ex => printErrorMessage(ex))
}

const renderUserContacts = () => {
    contactsNumber = 0
    getUserContacts()
        .then(contacts => {
            clearContacts()
            if (contacts && contacts.length > 0) {
                for (let i = 0; i < contacts.length; i++) {
                    renderContact(contacts[i])
                }
            } else renderContact(getVoidContact())
        })
}

const searchContact = () => {
    let phoneNumber = contactsForm.phoneNumber.value,
        url = `./ASSETS/PHP/CONTROLLERS/front-controller.php?search-contact=${phoneNumber}`,
        jwt = localStorage.getItem('jwt')

    contactsForm.reset()
    fetchHandler(url, getRequestData("GET", jwt))
        .then(res => {
            clearContacts()
            contactsNumber = 0
            renderContact(res)
            d.querySelector(".view-all-btn span").classList.remove("d-none")
        })
        .catch(ex => printErrorMessage(ex.error))
}

const clearContacts = () => {
    let contacts = d.querySelectorAll(".contact")
    contacts.forEach(contact => {
        contact.parentElement.removeChild(contact)
    });
}

const getVoidContact = () => {
    return {
        "firstname": "No contacts were found",
        "surname": "",
        "phoneNumber": ""
    }
}

const openContactChat = contactClick => {
    let contactPhoneNumber = contactClick.parentNode.querySelector(".contact-phone-number").textContent
    showChatPage()
    chatWindow.setAttribute("data_contactPhoneNumber", contactPhoneNumber)
    conversationLoop(contactPhoneNumber)
}

const renderContact = contact => {
    contactsNumber++
    contactsWindow.innerHTML +=
        `
        <div class="contact">
            <div class="contact-icon">
                <span class="material-symbols-outlined">
                    person
                </span>
            </div>
            <div class="contact-info">
                <p class="contact-name">${contact.firstname} ${contact.surname}</p>
                <p class="contact-phone-number">${contact.phoneNumber}</p>
            </div>
            <div class="contact-click contact-${contactsNumber}"></div>
        </div>
    `;
}

export {
    renderUserContacts,
    searchContact,
    openContactChat
}