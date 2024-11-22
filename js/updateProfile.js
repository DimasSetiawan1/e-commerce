/**
 * Updates the UI with a message using SweetAlert.
 * @param {Object} options - The options for the message.
 * @param {string} options.modalMessage - The message to display in the modal.
 * @param {string} options.modalTitle - The title of the modal.
 * @param {string} options.alertMessage - The message to display in the alert.
 * @param {string} options.alertTitle - The title of the alert.
 * @param {Function} options.updateFunction -  The function to update the field value.
 * @param {string} options.fieldName- The title of the alert.
 * @param {HTMLElement} formInput - The input element.
 *
 */
const updateMessage = ({
    modalMessage,
    modalTitle,
    updateFunction,
    fieldName,
    formInput,
}) => {
    Swal.fire({
        title: modalTitle,
        text: modalMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Save',
    }).then((result) => {
        if (result.isConfirmed) {
            formInput.blur()
            updateFunction()
            Swal.fire('Berhasil', `${fieldName} berhasil diperbarui`, 'success')
        }
    })
}

/**
 * Sets up form field editing functionality.
 * @param {HTMLElement} formInput - The input element.
 * @param {HTMLElement} btnEdit - The edit button element.
 * @param {string} fieldName - The name of the field.
 * @param {Function} validationFunction - The function to validate the field value.
 * @param {Function} updateFunction - The function to update the field value.
 */
const setFormField = (
    formInput,
    btnEdit,
    fieldName,
    validationFunction,
    updateFunction
) => {
    const originalValue = formInput.value
    const originalEmail =
        formInput.getAttribute('data-original-email') || originalValue
    const originalPhoneNumber =
        formInput.getAttribute('data-original-phone-number') || originalValue

    if (btnEdit.classList.contains('fa-pen-to-square')) {
        formInput.removeAttribute('disabled')
        btnEdit.classList.replace('fa-pen-to-square', 'fa-check-circle')
        if (fieldName.toLowerCase() === 'email') {
            formInput.value = originalEmail
        } else if (fieldName.toLowerCase() === 'phone') {
            formInput.value = originalPhoneNumber
        }
        formInput.focus()
    }

    const revertField = () => {
        formInput.value = originalValue
        formInput.setAttribute('disabled', '')
        btnEdit.classList.replace('fa-check-circle', 'fa-pen-to-square')
    }

    formInput.addEventListener('blur', revertField)

    formInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault()
            const fieldValue = formInput.value
            if (validationFunction(fieldValue)) {
                updateMessage({
                    modalMessage: `Apakah Anda yakin ingin menyimpan ${fieldName.toLowerCase()}?`,
                    modalTitle: 'Simpan Perubahan',
                    updateFunction: () =>
                        updateFunction(fieldValue, formInput, btnEdit),
                    fieldName: fieldName,
                    formInput: formInput,
                })
            } else {
                customAlert({
                    status: 'error',
                    title: 'Input Tidak Valid',
                    message: `Silakan masukkan ${fieldName.toLowerCase()} yang valid`,
                })

                formInput.focus()
            }
        } else if (e.key === 'Escape') {
            revertField()
        }
    })
}

/**
 * Updates a field value on the server.
 * @param {string} fieldValue - The new value for the field.
 * @param {HTMLElement} formInput - The input element.
 * @param {HTMLElement} btnEdit - The edit button element.
 * @param {string} fieldName - The name of the field.
 */
const updateField = (fieldValue, formInput, btnEdit, fieldName) => {
    const action =
        fieldName.toLowerCase() === 'email'
            ? 'update_email'
            : 'update_phone_number'

    fetch('./utils/profileHandler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        },
        body: `action=${action}&${fieldName.toLowerCase()}=${fieldValue}`,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                formInput.setAttribute('disabled', '')
                btnEdit.classList.replace('fa-check-circle', 'fa-pen-to-square')
            } else {
                throw new Error(
                    `An error occurred while updating ${fieldName.toLowerCase()}`
                )
            }
        })
        .catch((error) => {
            console.error('Error:', error)
            customAlert({
                title: ` Error updating ${fieldName.toLowerCase()}`,
                message: error.message,
                status: 'error',
            })
        })
}

/**
 * Sets up email editing functionality.
 * @param {string} email - The current email address.
 */

const setEmail = () => {
    const emailInput = document.getElementById('email')
    const editEmailBtn = document.getElementById('editEmail')

    const validateEmail = (email) => email.includes('@')
    const updateEmail = (email, formInput, btnEdit) => {
        const [nick, domain] = email.split('@')
        const maskedNick =
            nick.slice(0, 2) + '*'.repeat(Math.max(0, nick.length - 2))
        formInput.value = `${maskedNick}@${domain}`
        updateField(email, formInput, btnEdit, 'Email')
    }

    setFormField(emailInput, editEmailBtn, 'Email', validateEmail, updateEmail)
}

/**
 * Sets up phone number editing functionality.
 */
const setPhoneNumber = () => {
    const phoneInput = document.getElementById('phone_number')
    const editPhoneBtn = document.getElementById('editPhoneNumber')

    const validatePhone = (phone) => /^\d{10,}$/.test(phone)
    const updatePhone = (phone, formInput, btnEdit) => {
        const maskedPhone = '*'.repeat(phone.length - 2) + phone.slice(-2)
        formInput.value = maskedPhone
        updateField(phone, formInput, btnEdit, 'Phone')
    }

    setFormField(phoneInput, editPhoneBtn, 'Phone', validatePhone, updatePhone)
}

/**
 * Handles the name change process.
 */

function handleNameChange() {
    const savedButton = document.querySelector('.saved-text')
    const spinnerButton = document.querySelector('.spinner-border')
    const nameInput = document.getElementById('name')

    savedButton.classList.add('d-none')
    spinnerButton.classList.remove('d-none')

    fetch('./utils/profileHandler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=change_name&name=${encodeURIComponent(nameInput.value)}`,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Respon jaringan tidak baik')
            }
            return response.json()
        })
        .then((data) => {
            if (data.success) {
                setTimeout(() => {
                    spinnerButton.classList.add('d-none')
                    savedButton.classList.remove('d-none')
                    customAlert({
                        title: 'Berhasil',
                        message: 'Nama Anda berhasil diubah',
                        status: 'success',
                    })
                }, 1000)
            } else {
                throw new Error(
                    data.message || 'Terjadi kesalahan yang tidak diketahui'
                )
            }
        })
        .catch((error) => {
            console.error('Error:', error)
            customAlert({
                title: 'Gagal',
                message: error.message,
                status: 'error',
            })
        })
        .finally(() => {
            spinnerButton.classList.add('d-none')
            savedButton.classList.remove('d-none')
        })
}

/**
 * Change Password functionality.
 * @param {string} currentPassword - The current password.
 * @param {string} newPassword - The new password.
 * @param {string} confirmPassword - The confirm password.
 */

const changePassword = () => {
    const newPassword = document.getElementById('newPassword').value
    const confirmPassword = document.getElementById('confirmPassword').value
    const currentPassword = document.getElementById('currentPassword').value

    const savedButton = document.querySelector('.saved-text')
    const spinnerButton = document.querySelector('.spinner-border')

    if (newPassword !== confirmPassword) {
        savedButton.classList.add('d-none')
        spinnerButton.classList.remove('d-none')
        customAlert({
            status: 'error',
            title: 'Error',
            message: 'Password Tidak Sama',
        })
        exit()
    }
    savedButton.classList.add('d-none')
    spinnerButton.classList.remove('d-none')

    fetch('./utils/profileHandler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=change_password&password=${encodeURIComponent(
            currentPassword
        )}&new_password=${encodeURIComponent(newPassword)}`,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Respon jaringan tidak baik')
            }
            return response.json()
        })
        .then((data) => {
            if (String(data.message).includes('Password Salah')) {
                setTimeout(() => {
                    spinnerButton.classList.add('d-none')
                    savedButton.classList.remove('d-none')
                    customAlert({
                        title: 'Gagal',
                        message: data.message,
                        status: 'error',
                    })
                }, 1000)
            } else if (
                String(data.message).includes('Password berhasil diubah')
            ) {
                setTimeout(() => {
                    spinnerButton.classList.add('d-none')
                    savedButton.classList.remove('d-none')
                    customAlert({
                        title: 'Berhasil',
                        message: data.message,
                        status: 'success',
                    })
                }, 1000)
            } else {
                throw new Error(
                    data.message || 'Terjadi kesalahan yang tidak diketahui'
                )
            }
        })
}
