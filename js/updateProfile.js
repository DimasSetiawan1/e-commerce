const email = document.getElementById('email').value
const editEmailBtn = document.getElementById('editEmail')
const nick = email.split('@')[0]
const provider = email.split('@')[1]
document.getElementById('email').innerHTML = `${nick.charAt()}`
const maskedNick = nick.slice(0, 2) + '*'.repeat(Math.max(0, nick.length - 2))
const maskedEmail = `${maskedNick}@${provider}`

const phoneNumber = document.getElementById('phone_number')
const maskedPhoneNumber =
    '*'.repeat(phoneNumber.value.length - 2) + phoneNumber.value.slice(-2)
phoneNumber.value = maskedPhoneNumber

const alertUpdateProfile = document.getElementById('alertUpdateProfile')
let alertMessage = document.getElementById('alertMessages')

document.getElementById('email').value = maskedEmail

const modal = document.getElementById('confirmChangeModal')
const modalInstance = new mdb.Modal(modal)

function showConfirmModal(type) {
    const modalTitle = document.getElementById('confirmChangeModalLabel')
    const modalBody = document.getElementById('confirmChangeModalBody')

    switch (type) {
        case 'email':
            modalTitle.textContent = 'Confirm Email Change'
            modalBody.textContent =
                'Are you sure you want to change your email address?'
            break
        case 'phone':
            modalTitle.textContent = 'Confirm Phone Number Change'
            modalBody.textContent =
                'Are you sure you want to change your phone number?'
            break
    }

    modalInstance.show()
}

const setFormField = (
    formInput,
    btnEdit,
    alertMessage,
    alertUpdateProfile,
    fieldName,
    validationFunction,
    updateFunction
) => {
    formInput.removeAttribute('disabled')
    btnEdit.classList.remove('fa-pen-to-square')
    btnEdit.classList.add('fa-check-circle')
    const originalValue = formInput.value
    formInput.focus()

    const revertField = () => {
        formInput.value = originalValue
        formInput.setAttribute('disabled', '')
        btnEdit.classList.remove('fa-check-circle')
        btnEdit.classList.add('fa-pen-to-square')
    }

    formInput.addEventListener('blur', revertField)

    formInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault()
            const fieldValue = String(formInput.value)
            if (validationFunction(fieldValue)) {
                showConfirmModal(fieldName.toLowerCase())
                document.getElementById(`confirmChange`).onclick = () => {
                    formInput.blur()
                    updateFunction(
                        fieldValue,
                        formInput,
                        btnEdit,
                        alertMessage,
                        alertUpdateProfile
                    )
                    modalInstance.hide()
                }
            } else {
                alert(`Please enter a valid ${fieldName.toLowerCase()}`)
                formInput.focus()
            }
        } else if (e.key === 'Escape') {
            revertField()
        }
    })

    btnEdit.onclick = () => {
        if (btnEdit.classList.contains('fa-check-circle')) {
            updateFunction(
                String(formInput.value),
                formInput,
                btnEdit,
                alertMessage,
                alertUpdateProfile
            )
        } else {
            formInput.removeAttribute('disabled')
            btnEdit.classList.remove('fa-pen-to-square')
            btnEdit.classList.add('fa-check-circle')
            formInput.focus()
        }
    }
}

const updateField = (
    fieldValue,
    formInput,
    btnEdit,
    alertMessage,
    alertUpdateProfile,
    fieldName
) => {
    let action = ''
    if (String(fieldName).toLowerCase() === 'email') {
        action = 'update_email'
    } else if (String(fieldName).toLowerCase() === 'phone') {
        action = 'update_phone_number'
    }

    fetch('./utils/profileHandler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        },
        body: `action=${action}&${String(
            fieldName
        ).toLowerCase()}=${fieldValue}`,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data['success']) {
                alertMessage.innerText = `${String(
                    fieldName
                )} successfully changed`
                alertUpdateProfile.classList.remove('d-none')
                alertUpdateProfile.classList.replace('hide', 'show')
                setTimeout(() => {
                    alertUpdateProfile.classList.replace('show', 'hide')
                    alertUpdateProfile.classList.add('d-none')
                }, 2000)

                formInput.setAttribute('disabled', '')
                btnEdit.classList.remove('fa-check-circle')
                btnEdit.classList.add('fa-pen-to-square')
            } else {
                alert(
                    `An error occurred while updating ${String(
                        fieldName
                    ).toLowerCase()}`
                )
            }
        })
        .catch((error) => {
            console.error('Error:', error)
            alert(
                `An error occurred while updating ${String(
                    fieldName
                ).toLowerCase()}`
            )
            fieldValue
        })
}

const setEmail = () => {
    const emailInput = document.getElementById('email')
    const editEmailBtn = document.getElementById('editEmail')
    const alertMessage = document.getElementById('alertMessages')
    const alertUpdateProfile = document.getElementById('alertUpdateProfile')

    const validateEmail = (email) => email.includes('@')
    const updateEmail = (
        email,
        formInput,
        btnEdit,
        alertMessage,
        alertUpdateProfile
    ) => {
        const [nick, domain] = email.split('@')
        const maskedNick =
            nick.slice(0, 2) + '*'.repeat(Math.max(0, nick.length - 2))
        formInput.value = `${maskedNick}@${domain}`
        updateField(
            email,
            formInput,
            btnEdit,
            alertMessage,
            alertUpdateProfile,
            'Email'
        )
    }

    setFormField(
        emailInput,
        editEmailBtn,
        alertMessage,
        alertUpdateProfile,
        'Email',
        validateEmail,
        updateEmail
    )
}

const setPhoneNumber = () => {
    const phoneInput = document.getElementById('phone_number')
    const editPhoneBtn = document.getElementById('editPhoneNumber')
    const alertMessage = document.getElementById('alertMessages')
    const alertUpdateProfile = document.getElementById('alertUpdateProfile')

    const validatePhone = (phone) => /^\d{10,}$/.test(phone)
    const updatePhone = (
        phone,
        formInput,
        btnEdit,
        alertMessage,
        alertUpdateProfile
    ) => {
        const maskedPhone = '*'.repeat(phone.length - 2) + phone.slice(-2)
        formInput.value = maskedPhone
        updateField(
            phone,
            formInput,
            btnEdit,
            alertMessage,
            alertUpdateProfile,
            'Phone'
        )
    }

    setFormField(
        phoneInput,
        editPhoneBtn,
        alertMessage,
        alertUpdateProfile,
        'Phone',
        validatePhone,
        updatePhone
    )
}
