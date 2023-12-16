const d = document

const printSuccessMessage = (layer, successMessage) => {
    let successMessageContainer = d.querySelector(`.success-message.${layer}`)
    successMessageContainer.textContent = successMessage
    toggleMessageDisplay(successMessageContainer)
}

const printErrorMessage = error => {
    let errorMessageContainer = d.querySelector(`.error-message.${error.field}`)
    errorMessageContainer.textContent = error.message
    toggleMessageDisplay(errorMessageContainer)
}

const toggleMessageDisplay = async el => {
    el.classList.toggle("d-none")
    await new Promise(resolve => setTimeout(resolve, 3000))
    el.textContent = ""
    el.classList.toggle("d-none")
}

export {
    printSuccessMessage,
    printErrorMessage
}