import {
    fetchHandler,
    getRequestData
} from "./fetch.js"

import { printErrorMessage, printSuccessMessage } from "./alerts.js"
import {
    showLoginAuthenticationError,
    showContactsPage,
    showLoginPage
} from "./layers.js"

const d = document,
    loginForm = d.querySelector(".form.login-form"),
    registerForm = d.querySelector(".form.register-form")


const checkAuthenticationAndLoadInterface = () => {
    let jwt = localStorage.getItem("jwt")
    isUserAuthenticated(jwt)
        .then(authenticated => {
            if (authenticated) showContactsPage()
            else showLoginAuthenticationError()
        })
        .catch(ex => { showLoginAuthenticationError(ex) })
}

const isUserAuthenticated = async jwt => {
    let url = `./ASSETS/PHP/CONTROLLERS/front-controller.php?is-user-authenticated`

    return fetchHandler(url, getRequestData("GET", jwt))
        .then(isAuthenticated => isAuthenticated.isUserAuthenticated)
        .catch(ex => { throw ex })
}

const loginUser = () => {
    let url = `./ASSETS/PHP/CONTROLLERS/front-controller.php?authenticate-user`

    fetchHandler(url, getRequestData("POST", null, getLoginData()))
        .then(res => {
            loginForm.reset()
            localStorage.setItem("jwt", res.jwt)
            showContactsPage()
        })
        .catch(ex => printErrorMessage(ex.error))
}

const registerUser = () => {
    let url = `./ASSETS/PHP/CONTROLLERS/front-controller.php?register-user`

    fetchHandler(url, getRequestData("POST", null, getRegisterData()))
        .then(res => {
            registerForm.reset()
            showLoginPage()
            printSuccessMessage("login", res.message)
        })
        .catch(ex => printErrorMessage(ex.error))
}

const getLoginData = () => {
    return JSON.stringify({
        phoneNumber: loginForm.phoneNumber.value,
        password: loginForm.password.value
    })
}

const getRegisterData = () => {
    return JSON.stringify({
        firstname: registerForm.firstname.value,
        surname: registerForm.surname.value,
        phoneNumber: registerForm.phoneNumber.value,
        password: registerForm.password.value,
    })
}

const logout = () => {
    localStorage.removeItem('jwt')
    location.reload()
}

export {
    checkAuthenticationAndLoadInterface,
    loginUser,
    registerUser,
    logout
}